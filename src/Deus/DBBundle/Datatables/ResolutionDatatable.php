<?php

namespace Deus\DBBundle\Datatables;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;

/**
 * Class ResolutionDatatable
 * @Service("admin_resolution_datatable")
 * @Tag("sg.datatable.view")
 */
class ResolutionDatatable extends AbstractCrudDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        $this->setParameters();
        $this->setColumns();

        $this->setUrl($this->getRouter()->generate("admin_resolution_datatable"));

        //$this->setIndividualFiltering(true); // Uncomment it to have a search for each field

        $actions = [];
        if ($this->getRouter()->getRouteCollection()->get("admin_resolution_show") != null) {
            $actions[] = [
                "route" => "admin_resolution_show",
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
            ->add("value", "column", array("title" => $this->getTranslator()->trans("admin.resolution.value", [], 'admin')))
        ;
    }

    /**
    * {@inheritdoc}
    */
    public function getEntity()
    {
        return 'DeusDBBundle:Resolution';
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return "resolution_datatable";
    }
}