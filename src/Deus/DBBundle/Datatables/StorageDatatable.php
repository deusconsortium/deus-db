<?php

namespace Deus\DBBundle\Datatables;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;

/**
 * Class StorageDatatable
 * @Service("admin_storage_datatable")
 * @Tag("sg.datatable.view")
 */
class StorageDatatable extends AbstractCrudDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        $this->setParameters();
        $this->setColumns();

        $this->getAjax()->setUrl($this->getRouter()->generate("admin_storage_datatable"));

        //$this->setIndividualFiltering(true); // Uncomment it to have a search for each field

        $actions = [];
        if ($this->getRouter()->getRouteCollection()->get("admin_storage_show") != null) {
            $actions[] = [
                "route" => "admin_storage_show",
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
            ->add("name", "column", array("title" => $this->getTranslator()->trans("admin.storage.name", [], 'admin')))
            // ->add("Location.name", "column", array("title" => $this->getTranslator()->trans("admin.storage.Location", [], 'admin'))) Many to one, uncomment and select column to add
        ;
    }

    /**
    * {@inheritdoc}
    */
    public function getEntity()
    {
        return 'DeusDBBundle:Storage';
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return "storage_datatable";
    }
}