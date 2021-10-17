<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
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
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo :'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email :',
                'required' => true
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password :',
                'required' => true
            ])
            ->add('image', FileType::class, [
                'label' => 'Choice your avatar :',
                'mapped' => false
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
