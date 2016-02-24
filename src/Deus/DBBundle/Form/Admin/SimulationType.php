<?php

namespace Deus\DBBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SimulationType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*  */
        $builder
            ->add("Boxlen", "entity", [
                   'class'             => 'Deus\DBBundle\Entity\Boxlen',
//                   'searchRouteName'   => 'admin_simulation_boxlen_search',
//                   'property'          => '',
//                   'placeholder'       => 'search_placeholder',
//                   'required'          => false
               ])
            ->add("Resolution", "entity", [
                   'class'             => 'Deus\DBBundle\Entity\Resolution',
//                   'searchRouteName'   => 'admin_simulation_resolution_search',
//                   'property'          => '',
//                   'placeholder'       => 'search_placeholder',
//                   'required'          => false
               ])
            ->add("Cosmology", "entity", [
                   'class'             => 'Deus\DBBundle\Entity\Cosmology',
//                   'searchRouteName'   => 'admin_simulation_cosmology_search',
//                   'property'          => 'name',
//                   'placeholder'       => 'search_placeholder',
//                   'required'          => false
               ])
            ->add('public', null)
        //   ->add("geometries","collection_select2",[
        //           'class'             => 'Deus\DBBundle\Entity\Geometries',
        //           'searchRouteName'   => 'admin_geometries_search',
        //           'property'          => '',
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
            'data_class' => 'Deus\DBBundle\Entity\Simulation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_simulation';
    }
}
