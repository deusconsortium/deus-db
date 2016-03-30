<?php

namespace Sedona\SBOAdminThemeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SedonaSBOAdminThemeBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
