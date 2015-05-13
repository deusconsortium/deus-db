<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Deus\DBBundle\Entity\GeometryType;
use Deus\DBBundle\Form\Admin\GeometryTypeType;

/**
 * GeometryType controller.
 *
 * @Route("/geometrytype")
 */
class GeometryTypeController extends BaseCrudController
{
    protected $route_name = 'admin_geometrytype';
    protected $bundle_name = 'DeusDBBundle';
    protected $entity_name = 'GeometryType';

    /**
     * Lists all GeometryType entities.
     *
     * @Route("/", name="admin_geometrytype_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->manageIndex();
    }

    /**
     * JSON call for datatable to list all GeometryType entities.
     *
     * @Route("/datatable", name="admin_geometrytype_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        return $this->manageDatatableJson();
    }

    /**
    * Create a new GeometryType.
    *
    * @Route("/new", name="admin_geometrytype_new", options={"expose"=true})
    */
    public function newAction(Request $request)
    {
        return $this->manageNew(new GeometryType(), $request, new GeometryTypeType());
    }

    /**
    * Edit a GeometryType.
    *
    * @Route("/{id}/edit", name="admin_geometrytype_edit", options={"expose"=true})
    */
    public function editAction(GeometryType $entity, Request $request)
    {
        return $this->manageEdit($entity, $request, new GeometryTypeType());
    }

    /**
    * Show a GeometryType.
    *
    * @Route("/{id}", name="admin_geometrytype_show", options={"expose"=true})
    * @Method("GET")
    */
    public function showAction(GeometryType $entity)
    {
        return $this->manageShow($entity);
    }



}