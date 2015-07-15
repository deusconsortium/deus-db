<?php

namespace Deus\DBBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GeometryType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*  */
        $builder
            ->add('Z', null, ['required' => true])
            ->add('angle', null, ['required' => false])
            ->add("Simulation", "entity_select2", [
                   'class'             => 'Deus\DBBundle\Entity\Simulation',
                   'searchRouteName'   => 'admin_geometry_simulation_search',
                   'property'          => '',
                   'placeholder'       => 'search_placeholder',
                   'required'          => true
               ])
            ->add("GeometryType", "entity", [
                   'class'             => 'Deus\DBBundle\Entity\Geometrytype',
//                   'searchRouteName'   => 'admin_geometry_geometrytype_search',
                   'property'          => 'name',
                   'placeholder'       => 'search_placeholder',
                   'required'          => true
               ])
        //   ->add("objectGroups","collection_select2",[
        //           'class'             => 'Deus\DBBundle\Entity\Objectgroups',
        //           'searchRouteName'   => 'admin_objectgroups_search',
        //           'property'          => 'name',
        //           'required'          => false
        //       ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Deus\DBBundle\Entity\Geometry'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_geometry';
    }
}
