<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Deus\DBBundle\Entity\Location;
use Deus\DBBundle\Form\Admin\LocationType;

/**
 * Location controller.
 *
 * @Route("/location")
 */
class LocationController extends BaseCrudController
{
    protected $route_name = 'admin_location';
    protected $bundle_name = 'DeusDBBundle';
    protected $entity_name = 'Location';

    /**
     * Lists all Location entities.
     *
     * @Route("/", name="admin_location_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->manageIndex();
    }

    /**
     * JSON call for datatable to list all Location entities.
     *
     * @Route("/datatable", name="admin_location_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        return $this->manageDatatableJson();
    }

    /**
    * Create a new Location.
    *
    * @Route("/new", name="admin_location_new", options={"expose"=true})
    */
    public function newAction(Request $request)
    {
        return $this->manageNew(new Location(), $request, new LocationType());
    }

    /**
    * Edit a Location.
    *
    * @Route("/{id}/edit", name="admin_location_edit", options={"expose"=true})
    */
    public function editAction(Location $entity, Request $request)
    {
        return $this->manageEdit($entity, $request, new LocationType());
    }

    /**
    * Show a Location.
    *
    * @Route("/{id}", name="admin_location_show", options={"expose"=true})
    * @Method("GET")
    */
    public function showAction(Location $entity)
    {
        return $this->manageShow($entity);
    }



}