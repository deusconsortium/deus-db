<?php

namespace Deus\DBBundle\Service;

/**
 * Created by PhpStorm.
 * User: jpasdeloup
 * Date: 13/05/15
 * Time: 15:22
 */

use Deus\DBBundle\Entity\Simulation;
use Deus\DBBundle\Manager\DeusFileManager;
use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
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
     * @param EntityManagerInterface $em
     * @InjectParams({
            "em": @Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function explore($path, $checkFiles = true)
    {
        try {
            $simulation = new DeusFileManager($path, $checkFiles);
        }
        catch(ContextErrorException $error) {
            dump($error);
        }

        $simulation = $this->getSimulation(648, 'lcdmw5', 512);

        $this->em->flush();

        dump($simulation);
//
//        echo "LEN=".$simulation->getSimulationBoxlen()."\n";
//        echo "COSMO=".$simulation->getSimulationCosmo()."\n";
//        echo "COSMO=".$simulation->getSimulationResolution()."\n";
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
} 