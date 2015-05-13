<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Deus\DBBundle\Entity\Storage;
use Deus\DBBundle\Form\Admin\StorageType;
use Deus\DBBundle\Entity\Location;

/**
 * Storage controller.
 *
 * @Route("/storage")
 */
class StorageController extends BaseCrudController
{
    protected $route_name = 'admin_storage';
    protected $bundle_name = 'DeusDBBundle';
    protected $entity_name = 'Storage';

    /**
     * Lists all Storage entities.
     *
     * @Route("/", name="admin_storage_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->manageIndex();
    }

    /**
     * JSON call for datatable to list all Storage entities.
     *
     * @Route("/datatable", name="admin_storage_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        return $this->manageDatatableJson();
    }

    /**
    * Create a new Storage.
    *
    * @Route("/new", name="admin_storage_new", options={"expose"=true})
    */
    public function newAction(Request $request)
    {
        return $this->manageNew(new Storage(), $request, new StorageType());
    }

    /**
    * search Storage.
    *
    * @Route("/searchLocation", name="admin_storage_location_search", options={"expose"=true})
    *
    * @return JsonResponse
    */
    public function searchLocationAction(Request $request)
    {
        return $this->searchSelect2($request, 'Deus\DBBundle\Entity\Location', 'name');
    }        
    /**
    * Edit a Storage.
    *
    * @Route("/{id}/edit", name="admin_storage_edit", options={"expose"=true})
    */
    public function editAction(Storage $entity, Request $request)
    {
        return $this->manageEdit($entity, $request, new StorageType());
    }

    /**
    * Show a Storage.
    *
    * @Route("/{id}", name="admin_storage_show", options={"expose"=true})
    * @Method("GET")
    */
    public function showAction(Storage $entity)
    {
        return $this->manageShow($entity);
    }



}