<?php
/**
 * TaskInterface.php
 * avanzu-admin
 * Date: 23.02.14
 */

namespace Sedona\SBOAdminThemeBundle\Model;


interface TaskInterface {

    public function getColor();
    public function getProgress();
    public function getTitle();
    public function getIdentifier();
}