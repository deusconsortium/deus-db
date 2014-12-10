<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 07/12/14
 * Time: 18:27
 */

namespace Deus\DBBundle\EventListener;

use JMS\DiExtraBundle\Annotation\Observe;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class SimulationListener
 * @package Deus\DBBundle\EventListener
 * @Service("simulation_listener")
 */
class SimulationListener {

    /**
     * @param Event $e
     * @Observe("")
     */
    public function onCreateSimulation(Event $e)
    {

    }
} 