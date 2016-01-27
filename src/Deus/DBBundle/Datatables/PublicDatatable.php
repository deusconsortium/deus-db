<?php

namespace Deus\DBBundle\Datatables;

use Deus\DBBundle\Entity\Geometry;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class SimulationDatatable
 * @Service("public_datatable")
 * @Tag("sg.datatable.view")
 */
class PublicDatatable extends AbstractCrudDatatableView
{

    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        $this->setParameters();
        $this->setColumns();

        $this->ajax->setOptions(array(
            "url" => $this->getRouter()->generate("public_datatable"),
            "type" => "GET"
        ));

//        $this->setIndividualFiltering(true); // Uncomment it to have a search for each field

        $actions = [];
        if ($this->getRouter()->getRouteCollection()->get("public_show") != null) {
            $actions[] = [
                "route" => "public_show",
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
                    "title" => "Action",
                    "actions" => $actions
                ));
        }
    }

    protected function initLineFormatter()
    {
        $this->addLineFormatter(function($line) {
            $line['Geometry']['formattedZ'] = Geometry::formatZ($line['Geometry']['formattedZ']);

            $line['Geometry']['Simulation']['particleMass'] =
                $line['Geometry']['Simulation']['particleMass']
                    ? sprintf("%.2E",$line['Geometry']['Simulation']['particleMass'])
                    : '?';

            $line['Geometry']['Simulation']['Boxlen']['value'] = $line['Geometry']['Simulation']['Boxlen']['value'].'<sup>3</sup>';
            $line['Geometry']['Simulation']['Resolution']['value'] = $line['Geometry']['Simulation']['Resolution']['value'].'<sup>3</sup>';
            return $line;
        });
    }

    protected function setParameters()
    {
        $this->features->setFeatures(array(
            "auto_width" => true,
            "defer_render" => false,
            "info" => true,
            "jquery_ui" => false,
            "length_change" => true,
            "ordering" => true,
            "paging" => true,
            "processing" => true,
            "scroll_x" => true,
            "scroll_y" => "",
            "searching" => true,
            "server_side" => true,
            "state_save" => false,
            "delay" => 0
        ));
        $this->options->setOptions(array(
            "class" => Style::BOOTSTRAP_3_STYLE,
            "individual_filtering" => true,
            "individual_filtering_position" => "foot",
            "use_integration_options" => true,
            'page_length' => 50,
        ));

    }


    /**
     * {@inheritdoc}
     */
    protected function setColumns()
    {
        $em = $this->doctrine->getEntityManager();
        $cosmologies = $em->getRepository("DeusDBBundle:Cosmology")->findAll();
        $boxlens = $em->getRepository("DeusDBBundle:Boxlen")->findAll();
        $resolutions = $em->getRepository("DeusDBBundle:Resolution")->findAll();
        $geometryTypes = $em->getRepository("DeusDBBundle:GeometryType")->findAll();
        $objectType = $em->getRepository("DeusDBBundle:ObjectType")->findAll();
        $geometries = $em->getRepository("DeusDBBundle:Geometry")->findAll();
        $locations = $em->getRepository("DeusDBBundle:Location")->findAll();
        $simulations = $em->getRepository("DeusDBBundle:Simulation")->findAll();

        $this->getColumnBuilder()
            ->add("Geometry.Simulation.Cosmology.name", "column", array(
                "title" => $this->getTranslator()->trans("admin.simulation.Cosmology", [], 'admin'),
                "searchable" => true,
                "filter_type" => "select",
                "filter_options" => ["" => "Any"],// + $this->getCollectionAsOptionsArray($cosmologies, "name", "name"),
            ))
            ->add("Geometry.Simulation.Boxlen.value", "column", array(
                "title" => $this->getTranslator()->trans("admin.simulation.Boxlen", [], 'admin')." Mpc/h",
                "searchable" => true,
                "filter_type" => "select",
                "filter_options" => ["" => "Any"] + $this->getCollectionAsOptionsArray($boxlens, "value", "value"),
            ))
            ->add("Geometry.Simulation.Resolution.value", "column", array(
                "title" => $this->getTranslator()->trans("admin.simulation.Resolution", [], 'admin'),
                "searchable" => true,
                "filter_type" => "select",
                "filter_options" => ["" => "Any"] + $this->getCollectionAsOptionsArray($resolutions, "value", "value"),
            ))
            ->add("Geometry.Simulation.particleMass", "column", array(
                "title" => $this->getTranslator()->trans("admin.simulation.particleMass", [], 'admin'),
                "searchable" => true,
                "filter_type" => "select",
                "filter_options" => ["" => "Any"] + $this->getCollectionAsOptionsArrayForParticleMass($simulations, "particleMass", "particleMass"),
            ))
            ->add("Geometry.GeometryType.name", "column", array(
                "title" => $this->getTranslator()->trans("admin.geometrytype.entity_name", [], 'admin'),
                "searchable" => true,
                "filter_type" => "select",
                "filter_options" => ["" => "Any"] + $this->getCollectionAsOptionsArray($geometryTypes, "name", "name"),
            ))
            ->add("Geometry.formattedZ", "column", array(
                "title" => $this->getTranslator()->trans("admin.geometry.Z", [], 'admin'),
                "searchable" => true,
                "filter_type" => "select",
                "search_type" => "eq",
                "filter_options" => ["" => "Any"] + $this->getCollectionAsOptionsArrayForZ($geometries),
            ))
            ->add("ObjectType.name", "column", array(
                "title" => $this->getTranslator()->trans("admin.objecttype.entity_name", [], 'admin'),
                "searchable" => true,
                "filter_type" => "select",
                "search_type" => "eq",
                "filter_options" => ["" => "Any"] + $this->getCollectionAsOptionsArray($objectType, "name", "name")
            ))
            ->add("Storage.Location.name", "column", array(
                "title" => $this->getTranslator()->trans("admin.location.entity_name", [], 'admin'),
                "searchable" => true,
                "filter_type" => "select",
                "search_type" => "eq",
                "filter_options" => ["" => "Any"] + $this->getCollectionAsOptionsArray($locations, "name", "name")
            ))
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
        return "simulation_datatable";
    }

    /**
     * Returns options array based on key/value pairs, where key and value are the object's properties.
     *
     * @param ArrayCollection $entitiesCollection
     * @param string          $keyPropertyName
     * @param string          $valuePropertyName
     *
     * @return array
     */
    public function getCollectionAsOptionsArray($entitiesCollection, $keyPropertyName = 'id', $valuePropertyName = 'name')
    {
        $options = [];

        foreach ($entitiesCollection as $entity) {
            $keyPropertyName = Container::camelize($keyPropertyName);
            $keyGetter = 'get' . ucfirst($keyPropertyName);
            $valuePropertyName = Container::camelize($valuePropertyName);
            $valueGetter = 'get' . ucfirst($valuePropertyName);
            $options[$entity->$keyGetter()] = $entity->$valueGetter();
        }

        ksort($options);

        return $options;
    }

    public function getCollectionAsOptionsArrayForZ($entitiesCollection)
    {
        $options = [];

        foreach ($entitiesCollection as $entity) {
            $Z = (float) $entity->getFormattedZ();
            $value = (float) $Z;
            $options["$Z"] = $value;
        }

        asort($options);

        return $options;
    }

    public function getCollectionAsOptionsArrayForParticleMass($entitiesCollection)
    {
        $options = [];

        foreach ($entitiesCollection as $entity) {
            $Z = (float) $entity->getParticleMass();
            $value = (float) $Z;
            $options["$Z"] = sprintf("%.3E", $value);
        }

        asort($options);

        return $options;
    }
}