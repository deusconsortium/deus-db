<?php

namespace Deus\DBBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ObjectGroupType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*  */
        $builder
            ->add('name', null, ['required' => false])
            ->add('size', null, ['required' => true])
            ->add('nbFiles', null, ['required' => true])
            ->add("ObjectType", "entity_select2", [
                   'class'             => 'Deus\DBBundle\Entity\Objecttype',
                   'searchRouteName'   => 'admin_objectgroup_objecttype_search',
                   'property'          => 'name',
                   'placeholder'       => 'search_placeholder',
                   'required'          => false
               ])
            ->add("ObjectFormat", "entity_select2", [
                   'class'             => 'Deus\DBBundle\Entity\Objectformat',
                   'searchRouteName'   => 'admin_objectgroup_objectformat_search',
                   'property'          => 'name',
                   'placeholder'       => 'search_placeholder',
                   'required'          => false
               ])
            ->add("Geometry", "entity_select2", [
                   'class'             => 'Deus\DBBundle\Entity\Geometry',
                   'searchRouteName'   => 'admin_objectgroup_geometry_search',
                   'property'          => '',
                   'placeholder'       => 'search_placeholder',
                   'required'          => false
               ])
            ->add("Storage", "entity_select2", [
                   'class'             => 'Deus\DBBundle\Entity\Storage',
                   'searchRouteName'   => 'admin_objectgroup_storage_search',
                   'property'          => 'name',
                   'placeholder'       => 'search_placeholder',
                   'required'          => false
               ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Deus\DBBundle\Entity\ObjectGroup'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_objectgroup';
    }
}
