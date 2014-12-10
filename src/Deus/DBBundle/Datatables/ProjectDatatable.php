<?php

namespace Deus\DBBundle\Datatables;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;

/**
 * Class ProjectDatatable
 * @Service("admin_project_datatable")
 * @Tag("sg.datatable.view")
 */
class ProjectDatatable extends AbstractDatatableView
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

        $this->getAjax()->setUrl($this->getRouter()->generate("admin_project_datatable"));

        $this->setStyle(self::BOOTSTRAP_3_STYLE);

        $this->getColumnBuilder()
            ->add("name", "column", array("title" => $this->getTranslator()->trans("admin.project.name", [], 'admin')))
            ->add(null, "action", array(
                "title" => "Actions",
                "actions" => array(
                    array(
                        "route" => "admin_project_show",
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
        return 'DeusDBBundle:Project';
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return "project_datatable";
    }
}