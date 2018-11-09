<?php

namespace App\Form;

use App\Entity\ArticleAddNew;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleAddNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Tytuł'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Treść',
                'attr' => array('rows' => 10)
            ])
            ->add('image', FileType::class, [
                'label' => 'Obrazek'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Dodaj nowy',
                'attr' => array('class' => 'btn btn-success')
            ])
            ->add('reset', ResetType::class, [
                'label' => 'Wyczyść pola'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArticleAddNew::class,
        ]);
    }
}
