<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistEditType extends AbstractType
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
            ->add('biblio', TextareaType::class, [
                'label' => 'Bibliography :',
                'required' => true
            ])
            ->add('isDisabled', ChoiceType::class, [
                'label' => 'Ban Artist:',
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
