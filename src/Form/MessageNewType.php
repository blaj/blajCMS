<?php

namespace App\Form;

use App\Entity\MessageNew;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Tytuł wiadomości'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Treść wiadomości'
            ])
            ->add('toUser', TextType::class, [
                'label' => 'Odbiorca'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Wyślij wiadomość',
                'attr' => array('class' => 'btn btn-success')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MessageNew::class,
        ]);
    }
}
