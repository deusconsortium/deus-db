<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="admin_home" )
     */
    public function indexAction()
    {
        return $this->render("DeusDBBundle:Admin/Default:index.html.twig", array());
    }

}
