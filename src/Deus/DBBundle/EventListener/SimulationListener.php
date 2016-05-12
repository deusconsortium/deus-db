<?php

namespace Deus\DBBundle\EventListener;
use Deus\DBBundle\Entity\Simulation;
use Doctrine\ORM\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation\DoctrineListener;

/**
 * Class SimulationListener
 * @package Deus\DBBundle\EventListener
 * @DoctrineListener(events={"prePersist", "preUpdate"})

 */
class SimulationListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Simulation) {
            $entity->calculateParticleMass();
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Simulation) {
            $entity->calculateParticleMass();
        }
    }
} 