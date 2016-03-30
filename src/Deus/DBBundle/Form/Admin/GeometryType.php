<?php

namespace Deus\DBBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('Z', null, ['required' => false])
            ->add('angle', null, ['required' => false])
            ->add("Simulation", "entity", [
                   'class'             => 'Deus\DBBundle\Entity\Simulation',
                   'property'          => '',
                   'placeholder'       => 'search_placeholder',
                   'required'          => true
               ])
            ->add("GeometryType", "entity", [
                   'class'             => 'Deus\DBBundle\Entity\Geometrytype',
                   'property'          => 'name',
                   'placeholder'       => 'search_placeholder',
                   'required'          => true
               ])
            ->add('properties', TextareaType::class, ['required' => false])
            ->get("properties")->addModelTransformer(new CallbackTransformer(
                function ($originalProperties) {
                    $res = "";
                    if($originalProperties) {
                        foreach($originalProperties as $key => $value) {
                            $res .= "$key=$value\n";
                        }
                    }
                    return $res;
                },
                function ($submittedProperties) {
                    $res = [];
                    $lines = explode("\n", $submittedProperties);
                    foreach($lines as $oneLine) {
                        $values = explode("=",$oneLine,2);
                        if(2 == count($values)) {
                            $res[trim($values[0])] = trim($values[1]);
                        }
                    }
                    return $res;
                }
            ))
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
