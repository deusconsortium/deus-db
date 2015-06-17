<?php

namespace Deus\DBBundle\Datatables;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class SimulationDatatable
 * @Service("admin_simulation_datatable")
 * @Tag("sg.datatable.view")
 */
class SimulationDatatable extends AbstractCrudDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        $this->setParameters();
        $this->setColumns();

        $this->setUrl($this->getRouter()->generate("admin_simulation_datatable"));

        //$this->setIndividualFiltering(true); // Uncomment it to have a search for each field

        $actions = [];
        if ($this->getRouter()->getRouteCollection()->get("admin_simulation_show") != null) {
            $actions[] = [
                "route" => "admin_simulation_show",
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
        if(count($actions)>0) {
            $this->getColumnBuilder()
                ->add(null, "action", array(
                    "title" => "Actions",
                    "actions" => $actions
                ));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setColumns() {

        $this->getColumnBuilder()
             ->add("Boxlen.value", "column", array("title" => $this->getTranslator()->trans("admin.simulation.Boxlen", [], 'admin'))) //Many to one, uncomment and select column to add
             ->add("Resolution.value", "column", array("title" => $this->getTranslator()->trans("admin.simulation.Resolution", [], 'admin'))) //Many to one, uncomment and select column to add
             ->add("Cosmology.name", "column", array("title" => $this->getTranslator()->trans("admin.simulation.Cosmology", [], 'admin'))) //Many to one, uncomment and select column to add
        ;
    }

    /**
    * {@inheritdoc}
    */
    public function getEntity()
    {
        return 'DeusDBBundle:Simulation';
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return "simulation_datatable";
    }
}