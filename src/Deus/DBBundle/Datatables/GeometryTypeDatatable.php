<?php

namespace Deus\DBBundle\Datatables;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;

/**
 * Class GeometryTypeDatatable
 * @Service("admin_geometrytype_datatable")
 * @Tag("sg.datatable.view")
 */
class GeometryTypeDatatable extends AbstractCrudDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        $this->setParameters();
        $this->setColumns();

        $this->getAjax()->setUrl($this->getRouter()->generate("admin_geometrytype_datatable"));

        //$this->setIndividualFiltering(true); // Uncomment it to have a search for each field

        $actions = [];
        if ($this->getRouter()->getRouteCollection()->get("admin_geometrytype_show") != null) {
            $actions[] = [
                "route" => "admin_geometrytype_show",
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
            ->add("name", "column", array("title" => $this->getTranslator()->trans("admin.geometrytype.name", [], 'admin')))
        ;
    }

    /**
    * {@inheritdoc}
    */
    public function getEntity()
    {
        return 'DeusDBBundle:GeometryType';
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return "geometrytype_datatable";
    }
}