<?php

namespace App\Form\Lessor;

use App\Constants;
use App\Entity\PointOfInterest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PointOfInterestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', ChoiceType::class, [
                    'label' => 'Catégorie',
                    'choices' => Constants::getPointsOfInterestCategories(), ])
            ->add('label', null, [
                'label' => 'Désignation',
            ])
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PointOfInterest::class,
        ]);
    }
}
