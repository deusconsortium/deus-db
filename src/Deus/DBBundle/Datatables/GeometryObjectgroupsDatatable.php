<?php

namespace Deus\DBBundle\Datatables;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Deus\DBBundle\Entity\Geometry;

/**
 * Class GeometryDatatable
 * @Service("admin_geometry_objectGroups_datatable")
 * @Tag("sg.datatable.view")
 */
class GeometryObjectgroupsDatatable extends ObjectGroupDatatable
{

    /**
     * {@inheritdoc}
     */
    public function buildDatatableView(Geometry $geometry = null)
    {
        $this->setParameters();
        $this->setColumns();

        $this->getAjax()->setUrl($this->getRouter()->generate("admin_geometry_objectgroups_datatable",['id'=> $geometry->getId() ]));

        //$this->setIndividualFiltering(true); // Uncomment it to have a search for each field

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
        if ($this->getRouter()->getRouteCollection()->get("admin_geometry_objectgroups_remove") != null) {
            $actions[] = [
                "route" => "admin_geometry_objectgroups_remove",
                "route_parameters" => array("objectgroup_id" => "id", "id" => "Geometry.id" ),
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
            // mappedBy > Geometry | inversedBy > 
            $this->getColumnBuilder()
                ->add("Geometry.id","column",["visible" => false])
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
        return "geometry_objectgroups_datatable";
    }
}