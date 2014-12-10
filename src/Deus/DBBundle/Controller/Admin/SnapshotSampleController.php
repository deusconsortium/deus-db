<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Sedona\SBOGeneratorBundle\Controller\BaseCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Deus\DBBundle\Entity\SnapshotSample;
use Deus\DBBundle\Form\SnapshotSampleType;

/**
 * SnapshotSample controller.
 *
 * @Route("/snapshotsample")
 */
class SnapshotSampleController extends BaseCrudController
{
    protected $route_name = 'admin_snapshotsample';
    protected $bundle_name = 'DeusDBBundle';
    protected $entity_name = 'SnapshotSample';

    /**
     * Lists all SnapshotSample entities.
     *
     * @Route("/", name="admin_snapshotsample_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->manageIndex();
    }

    /**
     * JSON call for datatable to list all SnapshotSample entities.
     *
     * @Route("/datatable", name="admin_snapshotsample_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        return $this->manageDatatableJson();
    }

    /**
    * Create a new SnapshotSample.
    *
    * @Route("/new", name="admin_snapshotsample_new", options={"expose"=true})
    */
    public function newAction(Request $request)
    {
        return $this->manageNew(new SnapshotSample(), $request, new SnapshotSampleType());
    }

    /**
    * Show a SnapshotSample.
    *
    * @Route("/{id}", name="admin_snapshotsample_show", options={"expose"=true})
    * @Method("GET")
    */
    public function showAction(SnapshotSample $entity)
    {
        return $this->manageShow($entity);
    }

    /**
    * Edit a SnapshotSample.
    *
    * @Route("/{id}/edit", name="admin_snapshotsample_edit", options={"expose"=true})
    */
    public function editAction(SnapshotSample $entity, Request $request)
    {
        return $this->manageEdit($entity, $request, new SnapshotSampleType());
    }
}