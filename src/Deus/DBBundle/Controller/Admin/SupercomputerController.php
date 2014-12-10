<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Sedona\SBOGeneratorBundle\Controller\BaseCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Deus\DBBundle\Entity\Supercomputer;
use Deus\DBBundle\Form\SupercomputerType;

/**
 * Supercomputer controller.
 *
 * @Route("/supercomputer")
 */
class SupercomputerController extends BaseCrudController
{
    protected $route_name = 'admin_supercomputer';
    protected $bundle_name = 'DeusDBBundle';
    protected $entity_name = 'Supercomputer';

    /**
     * Lists all Supercomputer entities.
     *
     * @Route("/", name="admin_supercomputer_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->manageIndex();
    }

    /**
     * JSON call for datatable to list all Supercomputer entities.
     *
     * @Route("/datatable", name="admin_supercomputer_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        return $this->manageDatatableJson();
    }

    /**
    * Create a new Supercomputer.
    *
    * @Route("/new", name="admin_supercomputer_new", options={"expose"=true})
    */
    public function newAction(Request $request)
    {
        return $this->manageNew(new Supercomputer(), $request, new SupercomputerType());
    }

    /**
    * Show a Supercomputer.
    *
    * @Route("/{id}", name="admin_supercomputer_show", options={"expose"=true})
    * @Method("GET")
    */
    public function showAction(Supercomputer $entity)
    {
        return $this->manageShow($entity);
    }

    /**
    * Edit a Supercomputer.
    *
    * @Route("/{id}/edit", name="admin_supercomputer_edit", options={"expose"=true})
    */
    public function editAction(Supercomputer $entity, Request $request)
    {
        return $this->manageEdit($entity, $request, new SupercomputerType());
    }
}