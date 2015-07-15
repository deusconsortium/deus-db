<?php

namespace Deus\DBBundle\Datatables;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Deus\DBBundle\Entity\Simulation;

/**
 * Class SimulationDatatable
 * @Service("admin_simulation_geometries_datatable")
 * @Tag("sg.datatable.view")
 */
class SimulationGeometriesDatatable extends GeometryDatatable
{

    public function setMainEntity(Simulation $simulation = null)
    {
        $this->setUrl($this->getRouter()->generate("admin_simulation_geometries_datatable",['id'=> $simulation->getId() ]));
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        $this->setParameters();
        $this->setColumns();



        //$this->setIndividualFiltering(true); // Uncomment it to have a search for each field

        $actions = [];
        if ($this->getRouter()->getRouteCollection()->get("admin_geometry_show") != null) {
            $actions[] = [
                "route" => "admin_geometry_show",
                "route_parameters" => array("id" => "id"),
                "label" => $this->getTranslator()->trans("crud.title.show", [], 'admin'),
                "icon" => "glyphicon glyphicon-eye-open",
                "attributes" => array(
                    "rel" => "tooltip",
                    "title" => "Show",
                    "class" => "btn btn-default btn-xs",
                    "role" => "button"
                )
            ];
        }
        if ($this->getRouter()->getRouteCollection()->get("admin_simulation_geometries_remove") != null) {
            $actions[] = [
                "route" => "admin_simulation_geometries_remove",
                "route_parameters" => array("geometry_id" => "id", "id" => "Simulation.id" ),
                "label" => $this->getTranslator()->trans("crud.form.delete", [], 'admin'),
                "icon" => "glyphicon glyphicon-remove-circle",
                "attributes" => array(
                    "rel" => "tooltip",
                    "title" => $this->getTranslator()->trans("crud.form.delete", [], 'admin'),
                    "class" => "btn btn-default btn-xs",
                    "role" => "button",
                    "data-toggle" => "delete",
                    "data-confirm" => $this->getTranslator()->trans("crud.form.confirm", [], 'admin'),
                ),
            ];
        }
        if(count($actions)>0) {
            // mappedBy > Simulation | inversedBy > 
            $this->getColumnBuilder()
                ->add("Simulation.id","column",["visible" => false])
                ->add(null, "action", array(
                    "title" => "Actions",
                    "actions" => $actions
                ));
        }

    }


    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return "simulation_geometries_datatable";
    }
}