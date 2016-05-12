<?php

namespace Sedona\SBOAdminThemeBundle\Event;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Sedona Solutions (c) 2014
 * User: jpasdeloup
 * Date: 15/12/14
 * Time: 12:21
 */

class AdminCrudEvent extends Event
{
    const DONT_TOUCH = 0;
    const CREATE = 1;
    const UPDATE = 2;
    const DELETE = 3;

    /**
     * @var
     */
    private $item;
    /**
     * @var
     */
    private $action;
    /**
     * @var \Symfony\Component\Security\Core\User\UserInterface
     */
    private $user;

    /**
     * @param $item
     * @param $action
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     */
    public function __construct($item, $action, UserInterface $user = null)
    {
        $this->item = $item;
        $this->action = $action;
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param $action
     * @return mixed
     */
    public function setAction($action)
    {
        return $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
}