<?php

namespace App\Form;

use App\Entity\UserChangeEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserChangeEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainEmail', RepeatedType::class, [
                'type' => EmailType::class,
                'invalid_message' => 'E-Mail\'e nie są takie same!',
                'first_options'  => array('label' => 'Nowy E-Mail'),
                'second_options' => array('label' => 'Powtórz E-Mail'),
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zmień E-Mail',
                'attr' => array('class' => 'btn btn-success')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserChangeEmail::class,
        ]);
    }
}
