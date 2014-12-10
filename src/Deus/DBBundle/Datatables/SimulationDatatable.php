<?php

namespace Deus\DBBundle\Datatables;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;

/**
 * Class SimulationDatatable
 * @Service("admin_simulation_datatable")
 * @Tag("sg.datatable.view")
 */
class SimulationDatatable extends AbstractDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        $this->getFeatures()
            ->setServerSide(true)
            ->setProcessing(true)
        ;

        $this->getAjax()->setUrl($this->getRouter()->generate("admin_simulation_datatable"));

        $this->setStyle(self::BOOTSTRAP_3_STYLE);

        $this->getColumnBuilder()
            ->add("boxlen", "column", array("title" => $this->getTranslator()->trans("admin.simulation.boxlen", [], 'admin')))
            ->add("npart", "column", array("title" => $this->getTranslator()->trans("admin.simulation.npart", [], 'admin')))
            ->add("Supercomputer.name", "column", array("title" => $this->getTranslator()->trans("admin.simulation.Supercomputer", [], 'admin')))
            ->add("Project.name", "column", array("title" => $this->getTranslator()->trans("admin.simulation.Project", [], 'admin')))
            ->add("Cosmology.name", "column", array("title" => $this->getTranslator()->trans("admin.simulation.Cosmology", [], 'admin')))
            ->add(null, "action", array(
                "title" => "Actions",
                "actions" => array(
                    array(
                        "route" => "admin_simulation_show",
                        "route_parameters" => array("id" => "id"),
                        "label" => $this->getTranslator()->trans("crud.title.show", [], 'admin'),
                        "attributes" => array(
                        "rel" => "tooltip",
                        "title" => "Show",
                        "class" => "btn btn-default btn-xs",
                        "role" => "button"
                        ),
                    ),
                )
            ))
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