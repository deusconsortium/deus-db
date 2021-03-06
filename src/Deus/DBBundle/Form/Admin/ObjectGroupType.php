<?php

namespace Deus\DBBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('localPath', null, ['required' => true])
            ->add('size', TextType::class, ['required' => true])
            ->add('nbFiles', null, ['required' => true])
            ->add('nbGroups', null, ['required' => true])
            ->add('filePattern', null, ['required' => true])
            ->add('public', null)
            ->add("ObjectType", "entity", [
                   'class'             => 'Deus\DBBundle\Entity\Objecttype',
                   'property'          => 'name',
                   'placeholder'       => 'search_placeholder',
                   'required'          => false
               ])
            ->add("ObjectFormat", "entity", [
                   'class'             => 'Deus\DBBundle\Entity\Objectformat',
                   'property'          => 'name',
                   'placeholder'       => 'search_placeholder',
                   'required'          => false
               ])
            ->add("Geometry", "entity", [
                   'class'             => 'Deus\DBBundle\Entity\Geometry',
                   'property'          => '',
                   'placeholder'       => 'search_placeholder',
                   'required'          => false
               ])
            ->add("Storage", "entity", [
                   'class'             => 'Deus\DBBundle\Entity\Storage',
                   'property'          => 'name',
                   'placeholder'       => 'search_placeholder',
                   'required'          => false
               ])
            ->get("size")->addModelTransformer(new CallbackTransformer(
                function ($originalSize) {
                    return gmp_strval($originalSize);
                },
                function ($submittedSize) {
                    return gmp_init($submittedSize);
                }
            ))
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
