<?php

namespace Deus\DBBundle\Repository;

use Deus\DBBundle\Entity\Geometry;
use Deus\DBBundle\Entity\GeometryType;
use Deus\DBBundle\Entity\ObjectFormat;
use Deus\DBBundle\Entity\ObjectGroup;
use Deus\DBBundle\Entity\ObjectType;
use Deus\DBBundle\Entity\Simulation;
use Deus\DBBundle\Entity\Storage;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;

/**
 * Created by PhpStorm.
 * User: jean
 * Date: 08/02/16
 * Time: 17:43
 */

/**
 * Class SimulationRepository
 * @Service("deus.sim_repository")
 */
class SimulationRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var LoggerInterface
     */
    private $logger;

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

    public function getSimulation($boxlen_name, $cosmo_value, $npart_value)
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

    public function getObject($object, $property, $value)
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

    public function getSnapshot($simulation, $snapshotFile)
    {
        $geometryType = $this->em->getRepository("DeusDBBundle:GeometryType")->find(GeometryType::SNAPSHOT);
        if(isset($snapshotFile["infos"]) && isset($snapshotFile["infos"]["snapshot"]["Z"])) {
            $Z = $snapshotFile["infos"]["snapshot"]["Z"];
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

        if(!$snapshot->getProperties() || $snapshot->getProperties() == []) {
            $snapshot->setProperties($snapshotFile["infos"]["snapshot"]);
        }
        $Simulation = $snapshot->getSimulation();
        if(!$Simulation->getProperties() || $Simulation->getProperties() == []) {
            $Simulation->setProperties( $snapshotFile["infos"]['simulation']);
        }

        return $snapshot;
    }

    public function getCone($simulation, $snapshotFile, $type)
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

    public function getObjectGroup(Storage $storage, ObjectType $type, $path)
    {
        $localPath = substr($path,strlen($storage->getPath()));

        // Only FOF Format for file exploring
        $format = $this->em->getRepository("DeusDBBundle:ObjectFormat")->find(ObjectFormat::FOF);

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
    public function getStorage($path)
    {
        $storages = $this->em->getRepository("DeusDBBundle:Storage")->findBy([
            "Location" => $this->em->getRepository("DeusDBBundle:Location")->find("meudon")
        ]);

        foreach($storages as $storage) {
            if(strpos($path, $storage->getPath()) === 0 ) {
                return $storage;
            }
        }

        return null;
    }
}