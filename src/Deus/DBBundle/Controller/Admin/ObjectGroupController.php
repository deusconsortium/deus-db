<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Deus\DBBundle\Entity\ObjectGroup;
use Deus\DBBundle\Form\Admin\ObjectGroupType;
use Deus\DBBundle\Entity\ObjectType;
use Deus\DBBundle\Entity\ObjectFormat;
use Deus\DBBundle\Entity\Geometry;
use Deus\DBBundle\Entity\Storage;

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
    * search ObjectGroup.
    *
    * @Route("/searchObjecttype", name="admin_objectgroup_objecttype_search", options={"expose"=true})
    *
    * @return JsonResponse
    */
    public function searchObjecttypeAction(Request $request)
    {
        return $this->searchSelect2($request, 'Deus\DBBundle\Entity\ObjectType', 'name');
    }        
    /**
    * search ObjectGroup.
    *
    * @Route("/searchObjectformat", name="admin_objectgroup_objectformat_search", options={"expose"=true})
    *
    * @return JsonResponse
    */
    public function searchObjectformatAction(Request $request)
    {
        return $this->searchSelect2($request, 'Deus\DBBundle\Entity\ObjectFormat', 'name');
    }        
    /**
    * search ObjectGroup.
    *
    * @Route("/searchGeometry", name="admin_objectgroup_geometry_search", options={"expose"=true})
    *
    * @return JsonResponse
    */
    public function searchGeometryAction(Request $request)
    {
        return $this->searchSelect2($request, 'Deus\DBBundle\Entity\Geometry', '');
    }        
    /**
    * search ObjectGroup.
    *
    * @Route("/searchStorage", name="admin_objectgroup_storage_search", options={"expose"=true})
    *
    * @return JsonResponse
    */
    public function searchStorageAction(Request $request)
    {
        return $this->searchSelect2($request, 'Deus\DBBundle\Entity\Storage', 'name');
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



}