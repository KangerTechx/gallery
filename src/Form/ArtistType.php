<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Firstname :',
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Lastname :',
                'required' => true
            ])
            ->add('dnais', DateTimeType::class, [
                'label' => 'Day of birth :',
                'input' => 'datetime_immutable',
                'required' => true
            ])
            ->add('biblio', TextareaType::class, [
                'label' => 'Bibliography :',
                'required' => true
            ])
            ->add('image', FileType::class, [
                'label' => 'Avatar',
                'mapped' =>false,
                'required' => true
            ])
            ->add('isDisabled', ChoiceType::class, [
                'label' => 'Banish Artist:',
                'choices' => ['yes' => 1, 'no' => 0],
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
