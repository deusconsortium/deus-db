<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Sedona\SBOGeneratorBundle\Controller\BaseCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Deus\DBBundle\Entity\ObjectGroup;
use Deus\DBBundle\Form\ObjectGroupType;

/**
 * ObjectGroup controller.
 *
 * @Route("/objectgroup")
 */
class ObjectGroupController extends BaseCrudController
{
    protected $route_name = 'admin_objectgroup';
    protected $bundle_name = 'DeusDBBundle';
    protected $entity_name = 'ObjectGroup';

    /**
     * Lists all ObjectGroup entities.
     *
     * @Route("/", name="admin_objectgroup_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->manageIndex();
    }

    /**
     * JSON call for datatable to list all ObjectGroup entities.
     *
     * @Route("/datatable", name="admin_objectgroup_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        return $this->manageDatatableJson();
    }

    /**
    * Create a new ObjectGroup.
    *
    * @Route("/new", name="admin_objectgroup_new", options={"expose"=true})
    */
    public function newAction(Request $request)
    {
        return $this->manageNew(new ObjectGroup(), $request, new ObjectGroupType());
    }

    /**
    * Show a ObjectGroup.
    *
    * @Route("/{id}", name="admin_objectgroup_show", options={"expose"=true})
    * @Method("GET")
    */
    public function showAction(ObjectGroup $entity)
    {
        return $this->manageShow($entity);
    }

    /**
    * Edit a ObjectGroup.
    *
    * @Route("/{id}/edit", name="admin_objectgroup_edit", options={"expose"=true})
    */
    public function editAction(ObjectGroup $entity, Request $request)
    {
        return $this->manageEdit($entity, $request, new ObjectGroupType());
    }
}