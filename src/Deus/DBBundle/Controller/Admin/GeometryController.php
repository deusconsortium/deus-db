<?php

namespace Deus\DBBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Deus\DBBundle\Entity\Geometry;
use Deus\DBBundle\Form\Admin\GeometryType;
use Deus\DBBundle\Entity\Simulation;
//use Deus\DBBundle\Entity\GeometryType;
use Deus\DBBundle\Entity\ObjectGroup;

/**
 * Geometry controller.
 *
 * @Route("/geometry")
 */
class GeometryController extends BaseCrudController
{
    protected $route_name = 'admin_geometry';
    protected $bundle_name = 'DeusDBBundle';
    protected $entity_name = 'Geometry';

    /**
     * Lists all Geometry entities.
     *
     * @Route("/", name="admin_geometry_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->manageIndex();
    }

    /**
     * JSON call for datatable to list all Geometry entities.
     *
     * @Route("/datatable", name="admin_geometry_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        return $this->manageDatatableJson();
    }

    /**
     * Create a new Geometry.
     *
     * @Route("/new/simulation", name="admin_geometry_new", options={"expose"=true})
     */
    public function newAction(Request $request)
    {
        $geometry = new Geometry();
        return $this->manageNew($geometry, $request, new GeometryType());
    }

    /**
     * Create a new Geometry from Simulation.
     *
     * @Route("/new/simulation/{id}", name="admin_geometry_new_simulation", options={"expose"=true})
     */
    public function newFromSimulationAction(Simulation $simulation, Request $request)
    {
        $geometry = new Geometry();
        $geometry->setSimulation($simulation);
        return $this->manageNew($geometry, $request, new GeometryType());
    }

    /**
    * search Geometry.
    *
    * @Route("/searchSimulation", name="admin_geometry_simulation_search", options={"expose"=true})
    *
    * @return JsonResponse
    */
    public function searchSimulationAction(Request $request)
    {
        return $this->searchSelect2($request, 'Deus\DBBundle\Entity\Simulation', '');
    }        
    /**
    * search Geometry.
    *
    * @Route("/searchGeometrytype", name="admin_geometry_geometrytype_search", options={"expose"=true})
    *
    * @return JsonResponse
    */
    public function searchGeometrytypeAction(Request $request)
    {
        return $this->searchSelect2($request, 'Deus\DBBundle\Entity\GeometryType', 'name');
    }        
    /**
    * Edit a Geometry.
    *
    * @Route("/{id}/edit", name="admin_geometry_edit", options={"expose"=true})
    */
    public function editAction(Geometry $entity, Request $request)
    {
        return $this->manageEdit($entity, $request, new GeometryType());
    }

    /**
    * Show a Geometry.
    *
    * @Route("/{id}", name="admin_geometry_show", options={"expose"=true})
    * @Method("GET")
    */
    public function showAction(Geometry $entity)
    {
        return $this->manageShow($entity);
    }

            
    /**
     * Lists all ObjectGroup entities for property objectGroups of entity Geometry.
     *
     * @Route("/{id}/listObjectgroups", name="admin_geometry_objectgroups_list", options={"expose"=true})
     * @Method("GET")
     */
    public function indexObjectgroupsAction(Geometry $geometry)
    {
        return $this->manageFieldIndex($geometry, 'objectGroups');
    }

    /**
     * JSON call for datatable to list all ObjectGroup entities for property objectGroups of entity Geometry.
     *
     * @Route("/{id}/datatableObjectgroups", name="admin_geometry_objectgroups_datatable", options={"expose"=true})
     * @Method("GET")
     */
    public function datatableObjectgroupsAction(Geometry $geometry)
    {
        return $this->manageFieldDatatableJson($geometry, 'objectGroups', 'Geometry', 'one');
    }

    /**
     * Add relation Geometry to objectGroups.
     *
     * @Route("/{id}/addObjectgroups/{objectgroup_id}", name="admin_geometry_objectgroups_add", options={"expose"=true})
     * @ParamConverter("objectgroup", class="Deus\DBBundle\Entity\ObjectGroup", options={"id" = "objectgroup_id"})
     */
    public function addObjectgroupsAction(Geometry $geometry, ObjectGroup $objectgroup)
    {
        return $this->manageJsonAction($geometry, $objectgroup, 'objectGroups', 'addObjectgroups', false);
    }
            
    /**
     * Remove relation Geometry to objectGroups.
     *
     * @Route("/{id}/removeObjectgroups/{objectgroup_id}", name="admin_geometry_objectgroups_remove", options={"expose"=true})
     * @ParamConverter("objectgroup", class="Deus\DBBundle\Entity\ObjectGroup", options={"id" = "objectgroup_id"})
     */
    public function removeObjectgroupsAction(Geometry $geometry, ObjectGroup $objectgroup)
    {
        return $this->manageJsonAction($geometry, $objectgroup, 'objectGroups', 'removeObjectgroup', true);
    }    


}