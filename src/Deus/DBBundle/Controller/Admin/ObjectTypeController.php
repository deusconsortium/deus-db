<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Sedona\SBOGeneratorBundle\Controller\BaseCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Deus\DBBundle\Entity\ObjectType;
use Deus\DBBundle\Form\ObjectTypeType;

/**
 * ObjectType controller.
 *
 * @Route("/objecttype")
 */
class ObjectTypeController extends BaseCrudController
{
    protected $route_name = 'admin_objecttype';
    protected $bundle_name = 'DeusDBBundle';
    protected $entity_name = 'ObjectType';

    /**
     * Lists all ObjectType entities.
     *
     * @Route("/", name="admin_objecttype_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->manageIndex();
    }

    /**
     * JSON call for datatable to list all ObjectType entities.
     *
     * @Route("/datatable", name="admin_objecttype_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        return $this->manageDatatableJson();
    }

    /**
    * Create a new ObjectType.
    *
    * @Route("/new", name="admin_objecttype_new", options={"expose"=true})
    */
    public function newAction(Request $request)
    {
        return $this->manageNew(new ObjectType(), $request, new ObjectTypeType());
    }

    /**
    * Show a ObjectType.
    *
    * @Route("/{id}", name="admin_objecttype_show", options={"expose"=true})
    * @Method("GET")
    */
    public function showAction(ObjectType $entity)
    {
        return $this->manageShow($entity);
    }

    /**
    * Edit a ObjectType.
    *
    * @Route("/{id}/edit", name="admin_objecttype_edit", options={"expose"=true})
    */
    public function editAction(ObjectType $entity, Request $request)
    {
        return $this->manageEdit($entity, $request, new ObjectTypeType());
    }
}