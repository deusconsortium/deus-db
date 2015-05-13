<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Deus\DBBundle\Entity\ObjectFormat;
use Deus\DBBundle\Form\Admin\ObjectFormatType;

/**
 * ObjectFormat controller.
 *
 * @Route("/objectformat")
 */
class ObjectFormatController extends BaseCrudController
{
    protected $route_name = 'admin_objectformat';
    protected $bundle_name = 'DeusDBBundle';
    protected $entity_name = 'ObjectFormat';

    /**
     * Lists all ObjectFormat entities.
     *
     * @Route("/", name="admin_objectformat_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->manageIndex();
    }

    /**
     * JSON call for datatable to list all ObjectFormat entities.
     *
     * @Route("/datatable", name="admin_objectformat_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        return $this->manageDatatableJson();
    }

    /**
    * Create a new ObjectFormat.
    *
    * @Route("/new", name="admin_objectformat_new", options={"expose"=true})
    */
    public function newAction(Request $request)
    {
        return $this->manageNew(new ObjectFormat(), $request, new ObjectFormatType());
    }

    /**
    * Edit a ObjectFormat.
    *
    * @Route("/{id}/edit", name="admin_objectformat_edit", options={"expose"=true})
    */
    public function editAction(ObjectFormat $entity, Request $request)
    {
        return $this->manageEdit($entity, $request, new ObjectFormatType());
    }

    /**
    * Show a ObjectFormat.
    *
    * @Route("/{id}", name="admin_objectformat_show", options={"expose"=true})
    * @Method("GET")
    */
    public function showAction(ObjectFormat $entity)
    {
        return $this->manageShow($entity);
    }



}