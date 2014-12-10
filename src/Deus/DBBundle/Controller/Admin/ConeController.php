<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Sedona\SBOGeneratorBundle\Controller\BaseCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Deus\DBBundle\Entity\Cone;
use Deus\DBBundle\Form\ConeType;

/**
 * Cone controller.
 *
 * @Route("/cone")
 */
class ConeController extends BaseCrudController
{
    protected $route_name = 'admin_cone';
    protected $bundle_name = 'DeusDBBundle';
    protected $entity_name = 'Cone';

    /**
     * Lists all Cone entities.
     *
     * @Route("/", name="admin_cone_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->manageIndex();
    }

    /**
     * JSON call for datatable to list all Cone entities.
     *
     * @Route("/datatable", name="admin_cone_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        return $this->manageDatatableJson();
    }

    /**
    * Create a new Cone.
    *
    * @Route("/new", name="admin_cone_new", options={"expose"=true})
    */
    public function newAction(Request $request)
    {
        return $this->manageNew(new Cone(), $request, new ConeType());
    }

    /**
    * Show a Cone.
    *
    * @Route("/{id}", name="admin_cone_show", options={"expose"=true})
    * @Method("GET")
    */
    public function showAction(Cone $entity)
    {
        return $this->manageShow($entity);
    }

    /**
    * Edit a Cone.
    *
    * @Route("/{id}/edit", name="admin_cone_edit", options={"expose"=true})
    */
    public function editAction(Cone $entity, Request $request)
    {
        return $this->manageEdit($entity, $request, new ConeType());
    }
}