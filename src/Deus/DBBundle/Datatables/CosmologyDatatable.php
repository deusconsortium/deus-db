<?php

namespace Deus\DBBundle\Datatables;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;

/**
 * Class CosmologyDatatable
 * @Service("admin_cosmology_datatable")
 * @Tag("sg.datatable.view")
 */
class CosmologyDatatable extends AbstractCrudDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        $this->setParameters();
        $this->setColumns();

        $this->setUrl($this->getRouter()->generate("admin_cosmology_datatable"));

        //$this->setIndividualFiltering(true); // Uncomment it to have a search for each field

        $actions = [];
        if ($this->getRouter()->getRouteCollection()->get("admin_cosmology_show") != null) {
            $actions[] = [
                "route" => "admin_cosmology_show",
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
            ->add("name", "column", array("title" => $this->getTranslator()->trans("admin.cosmology.name", [], 'admin')))
        ;
    }

    /**
    * {@inheritdoc}
    */
    public function getEntity()
    {
        return 'DeusDBBundle:Cosmology';
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return "cosmology_datatable";
    }
}