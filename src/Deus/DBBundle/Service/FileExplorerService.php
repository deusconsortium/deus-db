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
use Deus\DBBundle\Entity\Simulation;
use Deus\DBBundle\Manager\DeusFileManager;
use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use Psr\Log\LoggerInterface;
use Symfony\Component\Debug\Exception\ContextErrorException;

/**
 * Class FileManagerService
 * @Service("deus.fileexplorer")
 */
class FileExplorerService
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

    public function explore($path, $checkFiles = true)
    {
        try {
            $simulationFiles = new DeusFileManager($path, $checkFiles);
        }
        catch(ContextErrorException $error) {
            $this->logger->error("DeusFileManager Error", (array) $error);
            dump($error);
        }

        dump($simulationFiles);

        $simulation = $this->getSimulation(
            $simulationFiles->getSimulationBoxlen(),
            $simulationFiles->getSimulationCosmo(),
            $simulationFiles->getSimulationResolution());

        foreach($simulationFiles->getSnaphots() as $oneSnapshot) {
            $snapshot = $this->getSnapshot($simulation, $oneSnapshot);
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
} 