<?php

namespace App\Form\Lessor;

use App\Entity\Equipment;
use App\Entity\Guarantor;
use App\Entity\HousingGroup;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HousingGroupFormType extends AbstractType
{
    public const MODE_EDIT = 'edit';
    public const MODE_CREATE = 'create';
    public const PRINCIPAL = 'principal';
    public const SERVICES = 'services';
    public const POI = 'poi';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (HousingGroupFormType::PRINCIPAL == $options['form_part']) {
            $builder
                ->add('name', null, [
                    'label' => 'Nom',
                ])
                ->add('address', AddressFormType::class)
                ->add('equipments', EntityType::class, [
                        'class' => Equipment::class,
                        'multiple' => true,
                        'expanded' => true,
                        'label_html' => true,
                        'choice_label' => function ($opt, $k, $v) {
                            return '<i class="'.$opt->getPicture().'"></i> '.$opt;
                        },
                    ])
                ->add('possibleGuarantor', EntityType::class, [
                        'class' => Guarantor::class,
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('om')->orderBy('om.sortOrder', 'ASC');
                        },
                        'multiple' => true,
                        'expanded' => true,
                        'label' => 'Garants possibles',
                        'required' => false,
                    ])

            ;
        }

        if (HousingGroupFormType::SERVICES == $options['form_part']) {
            $builder
                ->add('housingGroupServices', CollectionType::class, [
                    'entry_type' => HousingGroupServiceformType::class,
                    'allow_delete' => true,
                    'allow_add' => true,
                    'by_reference' => false,
                    'prototype' => true,
                    'entry_options' => ['label' => false],
                ]);
        }

        if (HousingGroupFormType::POI == $options['form_part']) {
            $builder
                ->add('pointsOfInterest', CollectionType::class, [
                    'entry_type' => PointOfInterestFormType::class,
                    'allow_delete' => true,
                    'allow_add' => true,
                    'by_reference' => false,
                    'prototype' => true,
                    'entry_options' => ['label' => false],
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HousingGroup::class,
            'form_part' => HousingGroupFormType::PRINCIPAL,
        ]);
    }
}
