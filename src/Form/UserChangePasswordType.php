<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserChangePassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'label' => 'Aktualne hasło'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Hasła nie są takie same!',
                'first_options'  => array('label' => 'Hasło'),
                'second_options' => array('label' => 'Powtórz hasło'),
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zmień hasło',
                'attr' => array('class' => 'btn btn-success')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserChangePassword::class,
        ]);
    }
}
