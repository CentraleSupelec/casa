<?php

namespace App\Form\Lessor;

use App\Entity\HousingGroupService;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HousingGroupServiceformType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isOptional', null, [
                'label' => 'En option',
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'label' => '',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HousingGroupService::class,
        ]);
    }
}
