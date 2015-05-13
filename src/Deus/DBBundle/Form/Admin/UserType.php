<?php

namespace Deus\DBBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*  */
        $builder
            ->add('username')
            ->add('usernameCanonical')
            ->add('email')
            ->add('emailCanonical')
            ->add('enabled')
            ->add('salt')
            ->add('password')
            ->add("lastLogin", "datetime", array('widget' => 'single_text', 'format' => "dd/MM/yyyy HH:mm:ss", 'required' => false))
            ->add('locked')
            ->add('expired')
            ->add("expiresAt", "datetime", array('widget' => 'single_text', 'format' => "dd/MM/yyyy HH:mm:ss", 'required' => false))
            ->add('confirmationToken', null, ['required' => false])
            ->add("passwordRequestedAt", "datetime", array('widget' => 'single_text', 'format' => "dd/MM/yyyy HH:mm:ss", 'required' => false))
            ->add('roles')
            ->add('credentialsExpired')
            ->add("credentialsExpireAt", "datetime", array('widget' => 'single_text', 'format' => "dd/MM/yyyy HH:mm:ss", 'required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Deus\DBBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_user';
    }
}
