<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nazwa użytkownika'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Hasła nie są takie same!',
                'first_options'  => array('label' => 'Hasło'),
                'second_options' => array('label' => 'Powtórz hasło'),
            ])
            ->add('plainEmail', RepeatedType::class, [
                'type' => EmailType::class,
                'invalid_message' => 'E-Mail\'e nie są takie same!',
                'first_options'  => array('label' => 'E-Mail'),
                'second_options' => array('label' => 'Powtórz E-Mail'),
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zarejestruj się',
                'attr' => array('class' => 'btn btn-success')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
