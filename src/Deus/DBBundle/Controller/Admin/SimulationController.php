<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Sedona\SBOGeneratorBundle\Controller\BaseCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Deus\DBBundle\Entity\Simulation;
use Deus\DBBundle\Form\SimulationType;

/**
 * Simulation controller.
 *
 * @Route("/simulation")
 */
class SimulationController extends BaseCrudController
{
    protected $route_name = 'admin_simulation';
    protected $bundle_name = 'DeusDBBundle';
    protected $entity_name = 'Simulation';

    /**
     * Lists all Simulation entities.
     *
     * @Route("/", name="admin_simulation_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->manageIndex();
    }

    /**
     * JSON call for datatable to list all Simulation entities.
     *
     * @Route("/datatable", name="admin_simulation_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        return $this->manageDatatableJson();
    }

    /**
    * Create a new Simulation.
    *
    * @Route("/new", name="admin_simulation_new", options={"expose"=true})
    */
    public function newAction(Request $request)
    {
        $entity = new Simulation();
        $form = new SimulationType();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm($form, $entity, array(
            'action' => $this->getNewUrl($entity),
            'method' => 'POST'
        ));

        $form
            ->add("create", "submit", array('attr' => array('class' => 'btn btn-primary')));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->addFlashMessage("success", "crud.message.created");

            return $this->redirect($this->getShowUrl($entity));
        }

        return $this->render(
            $this->getNewTemplate(),
            array(
                'entity'      => $entity,
                'form'   => $form->createView(),
            ));
    }

    /**
    * Show a Simulation.
    *
    * @Route("/{id}", name="admin_simulation_show", options={"expose"=true})
    * @Method("GET")
    */
    public function showAction(Simulation $entity)
    {
        return $this->manageShow($entity);
    }

    /**
    * Edit a Simulation.
    *
    * @Route("/{id}/edit", name="admin_simulation_edit", options={"expose"=true})
    */
    public function editAction(Simulation $entity, Request $request)
    {
        return $this->manageEdit($entity, $request, new SimulationType());
    }
}