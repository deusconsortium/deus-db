<?php

namespace Deus\DBBundle\Form;

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
        $builder
            ->add('boxlen')
            ->add('npart')
            ->add('Supercomputer')
            ->add('Project')
            ->add('Cosmology')
            ->add('baseDirectory')
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
