<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Deus\DBBundle\Entity\Resolution;
use Deus\DBBundle\Form\Admin\ResolutionType;

/**
 * Resolution controller.
 *
 * @Route("/resolution")
 */
class ResolutionController extends BaseCrudController
{
    protected $route_name = 'admin_resolution';
    protected $bundle_name = 'DeusDBBundle';
    protected $entity_name = 'Resolution';

    /**
     * Lists all Resolution entities.
     *
     * @Route("/", name="admin_resolution_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->manageIndex();
    }

    /**
     * JSON call for datatable to list all Resolution entities.
     *
     * @Route("/datatable", name="admin_resolution_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        return $this->manageDatatableJson();
    }

    /**
    * Create a new Resolution.
    *
    * @Route("/new", name="admin_resolution_new", options={"expose"=true})
    */
    public function newAction(Request $request)
    {
        return $this->manageNew(new Resolution(), $request, new ResolutionType());
    }

    /**
    * Edit a Resolution.
    *
    * @Route("/{id}/edit", name="admin_resolution_edit", options={"expose"=true})
    */
    public function editAction(Resolution $entity, Request $request)
    {
        return $this->manageEdit($entity, $request, new ResolutionType());
    }

    /**
    * Show a Resolution.
    *
    * @Route("/{id}", name="admin_resolution_show", options={"expose"=true})
    * @Method("GET")
    */
    public function showAction(Resolution $entity)
    {
        return $this->manageShow($entity);
    }



}