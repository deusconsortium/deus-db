<?php

namespace Deus\DBBundle\Datatables;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;

/**
 * Class ObjectGroupDatatable
 * @Service("admin_objectgroup_datatable")
 * @Tag("sg.datatable.view")
 */
class ObjectGroupDatatable extends AbstractCrudDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        $this->setParameters();
        $this->setColumns();

        $this->getAjax()->setUrl($this->getRouter()->generate("admin_objectgroup_datatable"));

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
            ->add("name", "column", array("title" => $this->getTranslator()->trans("admin.objectgroup.name", [], 'admin')))
            ->add("size", "column", array("title" => $this->getTranslator()->trans("admin.objectgroup.size", [], 'admin')))
            ->add("nbFiles", "column", array("title" => $this->getTranslator()->trans("admin.objectgroup.nbFiles", [], 'admin')))
            // ->add("ObjectType.name", "column", array("title" => $this->getTranslator()->trans("admin.objectgroup.ObjectType", [], 'admin'))) Many to one, uncomment and select column to add
            // ->add("ObjectFormat.name", "column", array("title" => $this->getTranslator()->trans("admin.objectgroup.ObjectFormat", [], 'admin'))) Many to one, uncomment and select column to add
            // ->add("Geometry.name", "column", array("title" => $this->getTranslator()->trans("admin.objectgroup.Geometry", [], 'admin'))) Many to one, uncomment and select column to add
            // ->add("Storage.name", "column", array("title" => $this->getTranslator()->trans("admin.objectgroup.Storage", [], 'admin'))) Many to one, uncomment and select column to add
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
        return "objectgroup_datatable";
    }
}