<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Deus\DBBundle\Entity\User;
use Deus\DBBundle\Form\Admin\UserType;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends BaseCrudController
{
    protected $route_name = 'admin_user';
    protected $bundle_name = 'DeusDBBundle';
    protected $entity_name = 'User';

    /**
     * Lists all User entities.
     *
     * @Route("/", name="admin_user_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->manageIndex();
    }

    /**
     * JSON call for datatable to list all User entities.
     *
     * @Route("/datatable", name="admin_user_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        return $this->manageDatatableJson();
    }

    /**
    * Create a new User.
    *
    * @Route("/new", name="admin_user_new", options={"expose"=true})
    */
    public function newAction(Request $request)
    {
        return $this->manageNew(new User(), $request, new UserType());
    }

    /**
    * Edit a User.
    *
    * @Route("/{id}/edit", name="admin_user_edit", options={"expose"=true})
    */
    public function editAction(User $entity, Request $request)
    {
        return $this->manageEdit($entity, $request, new UserType());
    }

    /**
    * Show a User.
    *
    * @Route("/{id}", name="admin_user_show", options={"expose"=true})
    * @Method("GET")
    */
    public function showAction(User $entity)
    {
        return $this->manageShow($entity);
    }



}