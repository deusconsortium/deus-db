<?php

namespace Deus\DBBundle\Datatables;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;

/**
 * Class GeometryDatatable
 * @Service("admin_geometry_datatable")
 * @Tag("sg.datatable.view")
 */
class GeometryDatatable extends AbstractCrudDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        $this->setParameters();
        $this->setColumns();

        $this->getAjax()->setUrl($this->getRouter()->generate("admin_geometry_datatable"));

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
            ->add("Z", "column", array("title" => $this->getTranslator()->trans("admin.geometry.Z", [], 'admin')))
            ->add("angle", "column", array("title" => $this->getTranslator()->trans("admin.geometry.angle", [], 'admin')))
            // ->add("Simulation.name", "column", array("title" => $this->getTranslator()->trans("admin.geometry.Simulation", [], 'admin'))) Many to one, uncomment and select column to add
            ->add("GeometryType.name", "column", array("title" => $this->getTranslator()->trans("admin.geometry.GeometryType", [], 'admin'))) //Many to one, uncomment and select column to add
        ;
    }

    /**
    * {@inheritdoc}
    */
    public function getEntity()
    {
        return 'DeusDBBundle:Geometry';
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return "geometry_datatable";
    }
}