<?php

namespace Deus\DBBundle\Datatables;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;

/**
 * Class SimulationDatatable
 * @Service("public_datatable")
 * @Tag("sg.datatable.view")
 */
class PublicDatatable extends AbstractCrudDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        $this->setParameters();
        $this->setColumns();

        $this->getAjax()->setUrl($this->getRouter()->generate("public_datatable"));

        $this->setIndividualFiltering(true); // Uncomment it to have a search for each field

        $actions = [];
        if ($this->getRouter()->getRouteCollection()->get("admin_objectgroup_show") != null) {
            $actions[] = [
                "route" => "admin_objectgroup_show",
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

    protected function setParameters() {
        $this->getFeatures()
            ->setServerSide(true)
            ->setProcessing(true)
        ;
        $this->setStyle(self::BOOTSTRAP_3_STYLE);
    }


    /**
     * {@inheritdoc}
     */
    protected function setColumns() {

        $this->getColumnBuilder()
            ->add("Geometry.Simulation.Cosmology.name", "column", array("title" => $this->getTranslator()->trans("admin.simulation.Cosmology", [], 'admin')))
            ->add("Geometry.Simulation.Boxlen.value", "column", array("title" => $this->getTranslator()->trans("admin.simulation.Boxlen", [], 'admin')." Mpc/h"))
            ->add("Geometry.Simulation.Resolution.value", "column", array("title" => $this->getTranslator()->trans("admin.simulation.Resolution", [], 'admin')))
            ->add("Geometry.GeometryType.name", "column", array("title" => $this->getTranslator()->trans("admin.geometrytype.entity_name", [], 'admin')))
            ->add("ObjectType.name", "column", array("title" => $this->getTranslator()->trans("admin.objectformat.entity_name", [], 'admin')))
        ;
    }

    /**
    * {@inheritdoc}
    */
    public function getEntity()
    {
        return 'DeusDBBundle:ObjectGroup';
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return "simulation_datatable";
    }
}