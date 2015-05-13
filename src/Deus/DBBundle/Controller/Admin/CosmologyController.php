<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Deus\DBBundle\Entity\Cosmology;
use Deus\DBBundle\Form\Admin\CosmologyType;

/**
 * Cosmology controller.
 *
 * @Route("/cosmology")
 */
class CosmologyController extends BaseCrudController
{
    protected $route_name = 'admin_cosmology';
    protected $bundle_name = 'DeusDBBundle';
    protected $entity_name = 'Cosmology';

    /**
     * Lists all Cosmology entities.
     *
     * @Route("/", name="admin_cosmology_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->manageIndex();
    }

    /**
     * JSON call for datatable to list all Cosmology entities.
     *
     * @Route("/datatable", name="admin_cosmology_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        return $this->manageDatatableJson();
    }

    /**
    * Create a new Cosmology.
    *
    * @Route("/new", name="admin_cosmology_new", options={"expose"=true})
    */
    public function newAction(Request $request)
    {
        return $this->manageNew(new Cosmology(), $request, new CosmologyType());
    }

    /**
    * Edit a Cosmology.
    *
    * @Route("/{id}/edit", name="admin_cosmology_edit", options={"expose"=true})
    */
    public function editAction(Cosmology $entity, Request $request)
    {
        return $this->manageEdit($entity, $request, new CosmologyType());
    }

    /**
    * Show a Cosmology.
    *
    * @Route("/{id}", name="admin_cosmology_show", options={"expose"=true})
    * @Method("GET")
    */
    public function showAction(Cosmology $entity)
    {
        return $this->manageShow($entity);
    }



}