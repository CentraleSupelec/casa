<?php

namespace App\Form;

use App\Entity\School;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',
                TextType::class,
                ['label' => 'profile.first_name',
                'required' => true, ])
            ->add('lastName', TextType::class, [
                'label' => 'profile.last_name',
                'required' => true, ])
            ->add('phone', TelType::class, [
                'label' => 'profile.phone',
                'required' => false,
            ])
            ->add('birthdate', BirthdayType::class, [
                'label' => 'profile.birthdate',
                'choice_translation_domain' => true,
                'widget' => 'single_text',
                'required' => false, ])
            ->add('school', EntityType::class, [
                'label' => 'profile.school',
                'required' => false,
                'class' => School::class,
                ])
            ->add('socialScholarship', null, [
                'label' => 'profile.social_scholarship',
                ])
            ->add('reducedMobility', null, [
                'label' => 'profile.reduced_mobility',
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
