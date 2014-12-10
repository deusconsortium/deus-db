<?php

namespace Deus\DBBundle\Datatables;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;

/**
 * Class SnapshotDatatable
 * @Service("admin_snapshot_datatable")
 * @Tag("sg.datatable.view")
 */
class SnapshotDatatable extends AbstractDatatableView
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

        $this->getAjax()->setUrl($this->getRouter()->generate("admin_snapshot_datatable"));

        $this->setStyle(self::BOOTSTRAP_3_STYLE);

        $this->getColumnBuilder()
            ->add("name", "column", array("title" => $this->getTranslator()->trans("admin.snapshot.name", [], 'admin')))
            ->add(null, "action", array(
                "title" => "Actions",
                "actions" => array(
                    array(
                        "route" => "admin_snapshot_show",
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
        return 'DeusDBBundle:Snapshot';
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return "snapshot_datatable";
    }
}