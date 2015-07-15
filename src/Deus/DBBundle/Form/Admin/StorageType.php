<?php

namespace Deus\DBBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StorageType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*  */
        $builder
            ->add('id', null, ['required' => true])
            ->add('name', null, ['required' => false])
            ->add('path', null, ['required' => false])
            ->add("Location", "entity", [
                   'class'             => 'Deus\DBBundle\Entity\Location',
//                   'searchRouteName'   => 'admin_storage_location_search',
//                   'property'          => 'name',
//                   'placeholder'       => 'search_placeholder',
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
            'data_class' => 'Deus\DBBundle\Entity\Storage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_storage';
    }
}
