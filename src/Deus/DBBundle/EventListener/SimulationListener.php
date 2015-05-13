<?php

namespace Deus\DBBundle\EventListener;
use Deus\DBBundle\Entity\Simulation;
use Deus\DBBundle\Manager\DeusFileManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation\DoctrineListener;

/**
 * Class SimulationListener
 * @package Deus\DBBundle\EventListener
 * @DoctrineListener(events={"prePersist"})

 */
class SimulationListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
//        $entity = $args->getEntity();
//        $entityManager = $args->getEntityManager();
//
//        if ($entity instanceof Simulation && $entity->getBaseDirectory() != "") {
//            $simulationManager = new DeusFileManager($entity->getBaseDirectory(),false);
//
//            echo "DIR=".$entity->getBaseDirectory()."<br/><br/>";
//
//            var_dump($simulationManager);
//            die("<br/><br/>done");
//
//        }
    }
} 