<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Sedona\SBOGeneratorBundle\Controller\BaseCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Deus\DBBundle\Entity\Snapshot;
use Deus\DBBundle\Form\SnapshotType;

/**
 * Snapshot controller.
 *
 * @Route("/snapshot")
 */
class SnapshotController extends BaseCrudController
{
    protected $route_name = 'admin_snapshot';
    protected $bundle_name = 'DeusDBBundle';
    protected $entity_name = 'Snapshot';

    /**
     * Lists all Snapshot entities.
     *
     * @Route("/", name="admin_snapshot_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->manageIndex();
    }

    /**
     * JSON call for datatable to list all Snapshot entities.
     *
     * @Route("/datatable", name="admin_snapshot_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        return $this->manageDatatableJson();
    }

    /**
    * Create a new Snapshot.
    *
    * @Route("/new", name="admin_snapshot_new", options={"expose"=true})
    */
    public function newAction(Request $request)
    {
        return $this->manageNew(new Snapshot(), $request, new SnapshotType());
    }

    /**
    * Show a Snapshot.
    *
    * @Route("/{id}", name="admin_snapshot_show", options={"expose"=true})
    * @Method("GET")
    */
    public function showAction(Snapshot $entity)
    {
        return $this->manageShow($entity);
    }

    /**
    * Edit a Snapshot.
    *
    * @Route("/{id}/edit", name="admin_snapshot_edit", options={"expose"=true})
    */
    public function editAction(Snapshot $entity, Request $request)
    {
        return $this->manageEdit($entity, $request, new SnapshotType());
    }
}