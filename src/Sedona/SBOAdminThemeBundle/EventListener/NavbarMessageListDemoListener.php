<?php
/**
 * NavbarMessageListDemoListener.php
 * avanzu-admin
 * Date: 23.02.14
 */

namespace Sedona\SBOAdminThemeBundle\EventListener;


use Sedona\SBOAdminThemeBundle\Event\MessageListEvent;
use Sedona\SBOAdminThemeBundle\Model\MessageModel;
use Sedona\SBOAdminThemeBundle\Model\UserModel;

class NavbarMessageListDemoListener {

    public function onListMessages(MessageListEvent $event) {

        foreach($this->getMessages() as $msg) {
            $event->addMessage($msg);
        }
    }

    protected function getMessages() {
        return array(
            new MessageModel(new UserModel('Karl kettenkit'),'Dude! do something!', new \DateTime('-3 days')),
            new MessageModel(new UserModel('Jack Trockendoc'),'This is some subject', new \DateTime('-10 month')),
        );
    }

}