<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Sedona\SBOGeneratorBundle\Controller\BaseCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Deus\DBBundle\Entity\Project;
use Deus\DBBundle\Form\ProjectType;

/**
 * Project controller.
 *
 * @Route("/project")
 */
class ProjectController extends BaseCrudController
{
    protected $route_name = 'admin_project';
    protected $bundle_name = 'DeusDBBundle';
    protected $entity_name = 'Project';

    /**
     * Lists all Project entities.
     *
     * @Route("/", name="admin_project_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->manageIndex();
    }

    /**
     * JSON call for datatable to list all Project entities.
     *
     * @Route("/datatable", name="admin_project_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        return $this->manageDatatableJson();
    }

    /**
    * Create a new Project.
    *
    * @Route("/new", name="admin_project_new", options={"expose"=true})
    */
    public function newAction(Request $request)
    {
        return $this->manageNew(new Project(), $request, new ProjectType());
    }

    /**
    * Show a Project.
    *
    * @Route("/{id}", name="admin_project_show", options={"expose"=true})
    * @Method("GET")
    */
    public function showAction(Project $entity)
    {
        return $this->manageShow($entity);
    }

    /**
    * Edit a Project.
    *
    * @Route("/{id}/edit", name="admin_project_edit", options={"expose"=true})
    */
    public function editAction(Project $entity, Request $request)
    {
        return $this->manageEdit($entity, $request, new ProjectType());
    }
}