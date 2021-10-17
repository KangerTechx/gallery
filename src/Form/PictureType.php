<?php

namespace App\Form;

use App\Entity\Picture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Picture title:',
                'required' => true
            ])
            ->add('width', NumberType::class, [
                'label' => 'Picture width:',
                'required' => true
            ])
            ->add('height', NumberType::class, [
                'label' => 'Picture height:',
                'required' => true
            ])
            ->add('image', FileType::class, [
                'label' => 'Choice your picture',
                'mapped' =>false,
                'required' => true
            ])
            ->add('smalldescript', TextType::class, [
                'label' => 'Small description:',
                'required' => true
            ])
            ->add('fulldescript', TextareaType::class, [
                'label' => 'Full description: ',
                'required' => true
            ])
            ->add('isPulished', ChoiceType::class, [
                'label' => 'Publish picture:',
                'choices' => ['yes' => 1, 'no' => 0],
                'required' => true
    ])
            ->add('artist', EntityType::class, [
                'label' => 'Select an Artist:',
                'placeholder' => 'Select ...',
                'class' => 'App:Artist',
                'choice_label' => 'lastname',
                'required' => true
            ])
            ->add('category', EntityType::class, [
                'label' => 'Select a category:',
                'placeholder' => 'Select ...',
                'class' => 'App:Category',
                'choice_label' => 'name',
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);
    }
}
