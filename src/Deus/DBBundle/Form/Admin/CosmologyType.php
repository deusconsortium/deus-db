<?php

namespace Deus\DBBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CosmologyType extends AbstractType
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
            ->add('url', null, ['required' => false])
            ->add('properties', TextareaType::class, ['required' => false])
            ->get("properties")->addModelTransformer(new CallbackTransformer(
                function ($originalProperties) {
                    $res = "";
                    if($originalProperties) {
                        foreach ($originalProperties as $key => $value) {
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
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Deus\DBBundle\Entity\Cosmology'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_cosmology';
    }
}
