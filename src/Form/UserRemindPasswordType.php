<?php

namespace App\Form;

use App\Entity\UserRemindPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRemindPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nazwa użytkownika'
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-Mail'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Resetuj hasło',
                'attr' => array('class' => 'btn btn-success')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserRemindPassword::class,
        ]);
    }
}
