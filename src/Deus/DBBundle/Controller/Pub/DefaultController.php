<?php

namespace Deus\DBBundle\Controller\Pub;

use Deus\DBBundle\Entity\ObjectGroup;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="public_home")
     */
    public function indexAction()
    {
        $postDatatable = $this->get('public_datatable');
        //$postDatatable->buildDatatableView();

        return $this->render("@DeusDB/Pub/index.html.twig", array(
            "datatable" => $postDatatable
        ));
    }

    /**
     * JSON call for datatable to list all Simulation entities.
     *
     * @Route("/datatable", name="public_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        $postDatatable = $this->get('public_datatable');
        $datatable = $this->get("sg_datatables.datatable")->getDatatable($postDatatable);

        return $datatable->getResponse();
    }

    /**
     * Show a ObjectGroup.
     *
     * @Route("/show/{id}", name="public_show", options={"expose"=true})
     * @Method("GET")
     */
    public function showAction(ObjectGroup $entity)
    {
        return $this->render("DeusDBBundle:Pub:show.html.twig", array(
            "entity" => $entity
        ));
    }
}
