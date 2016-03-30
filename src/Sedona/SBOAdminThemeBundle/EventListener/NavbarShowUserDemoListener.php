<?php
/**
 * NavbarShowUserListener.php
 * avanzu-admin
 * Date: 23.02.14
 */

namespace Sedona\SBOAdminThemeBundle\EventListener;


use Sedona\SBOAdminThemeBundle\Event\ShowUserEvent;
use Sedona\SBOAdminThemeBundle\Model\UserModel;

class NavbarShowUserDemoListener {

    public function onShowUser(ShowUserEvent $event) {

        $user = new UserModel();
        $user->setAvatar('')->setIsOnline(true)->setMemberSince(new \DateTime())->setUsername('Demo User');

        $event->setUser($user);
    }

}