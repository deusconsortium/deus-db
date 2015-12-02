<?php

namespace Deus\DBBundle\Service;

/**
 * Created by PhpStorm.
 * User: jpasdeloup
 * Date: 13/05/15
 * Time: 15:22
 */

use Deus\DBBundle\Entity\Geometry;
use Deus\DBBundle\Entity\GeometryType;
use Deus\DBBundle\Entity\ObjectFormat;
use Deus\DBBundle\Entity\ObjectType;
use Deus\DBBundle\Entity\ObjectGroup;
use Deus\DBBundle\Entity\Simulation;
use Deus\DBBundle\Entity\Storage;
use Deus\DBBundle\Manager\DeusArchiveManager;
use Deus\DBBundle\Manager\DeusFileManager;
use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use Psr\Log\LoggerInterface;
use Symfony\Component\Debug\Exception\ContextErrorException;

/**
 * Class FileManagerService
 * @Service("deus.archiveexplorer")
 */
class ArchiveExplorerService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var LoggerInterface
     */
    private $logger;

    private $objectFormat;

    /**
     * @param EntityManagerInterface $em
     * @InjectParams({
            "em": @Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function explore($path, $storage_id, $file_format_id)
    {
        try {
            $simulationFiles = new DeusArchiveManager($path);
        }
        catch(ContextErrorException $error) {

            die($error->getMessage());

            $this->logger->error("DeusFileManager Error", (array) $error);
            //dump($error);
            throw new \Exception("DeusFileManager Error");
        }

        $this->objectFormat = $file_format_id;
        $storage = $this->getStorage($storage_id);
        if(!$storage) {
            throw new \Exception("Storage not found for ".$storage_id );
        }

        $simulation = $this->getSimulation(
            $simulationFiles->getSimulationBoxlen(),
            $simulationFiles->getSimulationCosmo(),
            $simulationFiles->getSimulationResolution());

        foreach($simulationFiles->getSnaphots() as $oneSnapshot) {
            $snapshot = $this->getSnapshot($simulation, $oneSnapshot);
            if(isset($oneSnapshot["cube"]) && isset($oneSnapshot["cube"]["files"]) && isset($oneSnapshot["cube"]["path"])) {
                $this->logger->info("Adding ObjectGroup",["path" => $oneSnapshot["cube"]["path"]]);
                $type = $this->em->getRepository("DeusDBBundle:ObjectType")->find(ObjectType::CUBE);
                $objectGroup = $this->getObjectGroup($storage, $type, $oneSnapshot["cube"]["path"]);
                $objectGroup
                    ->setGeometry($snapshot)
                    ->setSize($oneSnapshot["cube"]["size"])
                    ->setNbFiles($oneSnapshot["cube"]["files"]);
            }

            if(isset($oneSnapshot["halos"][2000]) && isset($oneSnapshot["halos"][2000]["path"])) {
                $this->logger->info("Adding ObjectGroup",["path" => $oneSnapshot["halos"][2000]["path"]]);
                if(isset($oneSnapshot["halos"][2000]["nb_masst"]) && $oneSnapshot["halos"][2000]["nb_masst"] > 0) {
                    $type = $this->em->getRepository("DeusDBBundle:ObjectType")->find(ObjectType::MASST);
                    $objectGroup = $this->getObjectGroup($storage, $type, $oneSnapshot["halos"][2000]["path"]);
                    $objectGroup
                        ->setGeometry($snapshot)
                        ->setSize($oneSnapshot["halos"][2000]["size_masst"])
                        ->setNbFiles($oneSnapshot["halos"][2000]["nb_masst"]);

                }
                if(isset($oneSnapshot["halos"][2000]["nb_strct"]) && $oneSnapshot["halos"][2000]["nb_strct"] > 0) {
                    $type = $this->em->getRepository("DeusDBBundle:ObjectType")->find(ObjectType::STRCT);
                    $objectGroup = $this->getObjectGroup($storage, $type, $oneSnapshot["halos"][2000]["path"]);
                    $objectGroup
                        ->setGeometry($snapshot)
                        ->setSize($oneSnapshot["halos"][2000]["size_strct"])
                        ->setNbFiles($oneSnapshot["halos"][2000]["nb_strct"]);
                }
            }
        }

        foreach($simulationFiles->getCones() as $type => $oneSnapshot) {
            $snapshot = $this->getCone($simulation, $oneSnapshot, $type);
            if(isset($oneSnapshot["cube"]) && isset($oneSnapshot["cube"]["files"]) && isset($oneSnapshot["cube"]["path"])) {
                $this->logger->info("Adding ObjectGroup",["path" => $oneSnapshot["cube"]["path"]]);
                $type = $this->em->getRepository("DeusDBBundle:ObjectType")->find(ObjectType::CUBE);
                $objectGroup = $this->getObjectGroup($storage, $type, $oneSnapshot["cube"]["path"]);
                $objectGroup
                    ->setGeometry($snapshot)
                    ->setSize($oneSnapshot["cube"]["size"])
                    ->setNbFiles($oneSnapshot["cube"]["files"]);
            }

            if(isset($oneSnapshot["halos"][2000]) && isset($oneSnapshot["halos"][2000]["path"])) {
                $this->logger->info("Adding ObjectGroup",["path" => $oneSnapshot["halos"][2000]["path"]]);
                if(isset($oneSnapshot["halos"][2000]["nb_masst"])) {
                    $type = $this->em->getRepository("DeusDBBundle:ObjectType")->find(ObjectType::MASST);
                    $objectGroup = $this->getObjectGroup($storage, $type, $oneSnapshot["halos"][2000]["path"]);
                    $objectGroup
                        ->setGeometry($snapshot)
                        ->setSize($oneSnapshot["halos"][2000]["size_masst"])
                        ->setNbFiles($oneSnapshot["halos"][2000]["nb_masst"]);

                }
                if(isset($oneSnapshot["halos"][2000]["nb_strct"])) {
                    $type = $this->em->getRepository("DeusDBBundle:ObjectType")->find(ObjectType::STRCT);
                    $objectGroup = $this->getObjectGroup($storage, $type, $oneSnapshot["halos"][2000]["path"]);
                    $objectGroup
                        ->setGeometry($snapshot)
                        ->setSize($oneSnapshot["halos"][2000]["size_strct"])
                        ->setNbFiles($oneSnapshot["halos"][2000]["nb_strct"]);
                }
            }
        }

        $this->em->flush();
    }

    protected function getSimulation($boxlen_name, $cosmo_value, $npart_value)
    {
        $Boxlen = $this->getObject("Boxlen","value",$boxlen_name);
        $Cosmology = $this->getObject("Cosmology","name",$cosmo_value);
        $Resolution = $this->getObject("Resolution","value",$npart_value);

        $simulation = $this->em->getRepository("DeusDBBundle:Simulation")->findOneBy([
            'Boxlen' => $Boxlen,
            'Cosmology' => $Cosmology,
            'Resolution' => $Resolution,
        ]);
        if(!$simulation) {
            $simulation = new Simulation();
            $simulation
                ->setBoxlen($Boxlen)
                ->setResolution($Resolution)
                ->setCosmology($Cosmology);
            $this->em->persist($simulation);
        }

        return $simulation;
    }

    protected function getObject($object, $property, $value)
    {
        $res = $this->em->getRepository("DeusDBBundle:".$object)->findOneBy([$property => $value]);
        if(!$res) {
            $object = 'Deus\\DBBundle\\Entity\\'.$object;
            $res = new $object;
            $res->{'set'.ucfirst($property)}($value);
            $this->em->persist($res);
        }
        return $res;
    }

    private function getSnapshot($simulation, $snapshotFile)
    {
        $geometryType = $this->em->getRepository("DeusDBBundle:GeometryType")->find(GeometryType::SNAPSHOT);
        if(isset($snapshotFile["infos"]) && isset($snapshotFile["infos"]["Z"])) {
            $Z = $snapshotFile["infos"]["Z"];
        }
        else {
            $this->logger->warning("No Z for snapshot", (array) $snapshotFile);
            return null;
        }

        $snapshot = $this->em->getRepository("DeusDBBundle:Geometry")->findOneBy([
            'GeometryType' => $geometryType,
            'Simulation' => $simulation,
            'Z' => $Z,
        ]);
        if(!$snapshot) {
            $snapshot = new Geometry();
            $snapshot
                ->setGeometryType($geometryType)
                ->setSimulation($simulation)
                ->setZ($Z);
            $this->em->persist($snapshot);
        }
        return $snapshot;
    }

    private function getCone($simulation, $snapshotFile, $type)
    {
        $geometryType = $this->em->getRepository("DeusDBBundle:GeometryType")->find(GeometryType::CONE);
        $Z = 0;

        if($type == "narrow") {
            $angle = Geometry::NARROW;
        }
        else {
            $angle = Geometry::FULLSKY;
        }

        $snapshot = $this->em->getRepository("DeusDBBundle:Geometry")->findOneBy([
            'GeometryType' => $geometryType,
            'Simulation' => $simulation,
            'Z' => $Z,
            'angle' => $angle
        ]);
        if(!$snapshot) {
            $snapshot = new Geometry();
            $snapshot
                ->setGeometryType($geometryType)
                ->setSimulation($simulation)
                ->setZ($Z)
                ->setAngle($angle);
            $this->em->persist($snapshot);
        }
        return $snapshot;
    }

    private function getObjectGroup(Storage $storage, ObjectType $type, $path)
    {
        $localPath = substr($path,strlen($storage->getPath()));

        // Only FOF Format for file exploring
        $format = $this->em->getRepository("DeusDBBundle:ObjectFormat")->find($this->objectFormat);

        $objectGroup = $this->em->getRepository("DeusDBBundle:ObjectGroup")->findOneBy([
            'Storage' => $storage,
            'localPath' => $localPath,
            'ObjectType' => $type
        ]);

        if(!$objectGroup) {
            $objectGroup = new ObjectGroup();
            $objectGroup
                ->setStorage($storage)
                ->setObjectType($type)
                ->setLocalPath($localPath)
                ->setObjectFormat($format);
            $this->em->persist($objectGroup);
        }

        return $objectGroup;
    }

    /**
     * @param $path
     * @return \Deus\DBBundle\Entity\Storage|null
     */
    private function getStorage($storage_id)
    {
        $storage = $this->em->getRepository("DeusDBBundle:Storage")->find($storage_id);

        return $storage;
    }
}
