<?php
/**
 * ShowUserEvent.php
 * avanzu-admin
 * Date: 23.02.14
 */

namespace Sedona\SBOAdminThemeBundle\Event;


use Sedona\SBOAdminThemeBundle\Model\UserInterface;

class ShowUserEvent extends  ThemeEvent {

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * @param \Sedona\SBOAdminThemeBundle\Model\UserInterface $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return \Sedona\SBOAdminThemeBundle\Model\UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }


}