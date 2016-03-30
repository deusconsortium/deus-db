<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Deus\DBBundle\Entity\Simulation;
use Deus\DBBundle\Form\Admin\SimulationType;
use Deus\DBBundle\Entity\Boxlen;
use Deus\DBBundle\Entity\Resolution;
use Deus\DBBundle\Entity\Cosmology;
use Deus\DBBundle\Entity\Geometry;

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
        return $this->manageNew(new Simulation(), $request, new SimulationType());
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
     * Lists all Geometry entities for property geometries of entity Simulation.
     *
     * @Route("/{id}/listGeometries", name="admin_simulation_geometries_list", options={"expose"=true})
     * @Method("GET")
     */
    public function indexGeometriesAction(Simulation $simulation)
    {
        return $this->manageFieldIndex($simulation, 'geometries');
    }

    /**
     * JSON call for datatable to list all Geometry entities for property geometries of entity Simulation.
     *
     * @Route("/{id}/datatableGeometries", name="admin_simulation_geometries_datatable", options={"expose"=true})
     * @Method("GET")
     */
    public function datatableGeometriesAction(Simulation $simulation)
    {
        return $this->manageFieldDatatableJson($simulation, 'geometries', 'Simulation', 'one');
    }

    /**
     * Add relation Simulation to geometries.
     *
     * @Route("/{id}/addGeometries/{geometry_id}", name="admin_simulation_geometries_add", options={"expose"=true})
     * @ParamConverter("geometry", class="Deus\DBBundle\Entity\Geometry", options={"id" = "geometry_id"})
     */
    public function addGeometriesAction(Simulation $simulation, Geometry $geometry)
    {
        return $this->manageJsonAction($simulation, $geometry, 'geometries', 'addGeometry', false);
    }
            
    /**
     * Remove relation Simulation to geometries.
     *
     * @Route("/{id}/removeGeometries/{geometry_id}", name="admin_simulation_geometries_remove", options={"expose"=true})
     * @ParamConverter("geometry", class="Deus\DBBundle\Entity\Geometry", options={"id" = "geometry_id"})
     */
    public function removeGeometriesAction(Simulation $simulation, Geometry $geometry)
    {
        return $this->manageJsonAction($simulation, $geometry, 'geometries', 'removeGeometry', true);
    }    


}