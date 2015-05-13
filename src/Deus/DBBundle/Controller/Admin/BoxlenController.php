<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Deus\DBBundle\Entity\Boxlen;
use Deus\DBBundle\Form\Admin\BoxlenType;

/**
 * Boxlen controller.
 *
 * @Route("/boxlen")
 */
class BoxlenController extends BaseCrudController
{
    protected $route_name = 'admin_boxlen';
    protected $bundle_name = 'DeusDBBundle';
    protected $entity_name = 'Boxlen';

    /**
     * Lists all Boxlen entities.
     *
     * @Route("/", name="admin_boxlen_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->manageIndex();
    }

    /**
     * JSON call for datatable to list all Boxlen entities.
     *
     * @Route("/datatable", name="admin_boxlen_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        return $this->manageDatatableJson();
    }

    /**
    * Create a new Boxlen.
    *
    * @Route("/new", name="admin_boxlen_new", options={"expose"=true})
    */
    public function newAction(Request $request)
    {
        return $this->manageNew(new Boxlen(), $request, new BoxlenType());
    }

    /**
    * Edit a Boxlen.
    *
    * @Route("/{id}/edit", name="admin_boxlen_edit", options={"expose"=true})
    */
    public function editAction(Boxlen $entity, Request $request)
    {
        return $this->manageEdit($entity, $request, new BoxlenType());
    }

    /**
    * Show a Boxlen.
    *
    * @Route("/{id}", name="admin_boxlen_show", options={"expose"=true})
    * @Method("GET")
    */
    public function showAction(Boxlen $entity)
    {
        return $this->manageShow($entity);
    }



}