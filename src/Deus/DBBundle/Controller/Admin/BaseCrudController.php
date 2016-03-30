<?php

namespace Deus\DBBundle\Controller\Admin;

/**
 * Base CRUD Controller
 */
abstract class BaseCrudController extends SourceBaseCrudController
{
    // Put your code here to override standard features

    /**
     * Generic index controller helper
     * @return Response
     */
    protected function manageIndex()
    {
        $postDatatable = $this->get($this->route_name . '_datatable');
        //$postDatatable->buildDatatableView();

        return $this->render($this->getIndexTemplate(), array(
            "datatable" => $postDatatable
        ));
    }

    /**
     * Generic index controller helper
     * @param $entity
     * @param $field
     * @return Response
     * @throws \Exception
     */
    protected function manageFieldIndex($entity, $field)
    {
        if ($entity == null || $field == null || $this->has($this->route_name.'_'.$field.'_datatable') == false) {
            throw new \Exception("All the parameters are not correctly set");
        }
        $postDatatable = $this->get($this->route_name.'_'.$field.'_datatable');
        $postDatatable->setMainEntity($entity);
        //$postDatatable->buildDatatableView($entity);

        return $this->render($this->getIndexTemplate(strtolower($field)), array(
            "datatable" => $postDatatable,
            "entity"    => $entity
        ));
    }

}
