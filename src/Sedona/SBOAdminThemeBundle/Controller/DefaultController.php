<?php

namespace Sedona\SBOAdminThemeBundle\Controller;

use Sedona\SBOAdminThemeBundle\Form\FormDemoModelType;
use Sedona\SBOAdminThemeBundle\Model\FormDemoModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class DefaultController
 *
 * @package Sedona\SBOAdminThemeBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dashboardAction() {
        return    $this->render('SedonaSBOAdminThemeBundle:Default:index.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function uiGeneralAction() {
        return $this->render('SedonaSBOAdminThemeBundle:Default:index.html.twig');
    }

    public function uiIconsAction() {
        return $this->render('SedonaSBOAdminThemeBundle:Default:index.html.twig');
    }

    public function formAction() {
        $form =$this->createForm( new FormDemoModelType());
        return $this->render('SedonaSBOAdminThemeBundle:Default:form.html.twig', array(
                'form' => $form->createView()
            ));
    }
}
