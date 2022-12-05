<?php

namespace App\Form\Lessor;

use App\Constants;
use App\Entity\Equipment;
use App\Entity\Housing;
use App\Entity\HousingGroup;
use App\Entity\OccupationMode;
use App\Entity\StayDuration;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HousingFormType extends AbstractType
{
    public const PRINCIPAL = 'principal';
    public const PHOTO = 'photos';
    public const SCHOOLCRITERION = 'schoolcriterion';
    public const SOCIALSCHOLARSHIP = 'socialscholarship';

    public const MODE_EDIT = 'edit';
    public const MODE_CREATE = 'create';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $lessor = $builder->getData()->getHousingGroup()->getLessor();
        $housingEquipments = $builder->getData()->getHousingGroup()->getEquipments()->toArray();

        if (HousingFormType::PRINCIPAL == $options['form_part']) {
            $builder
                ->add('housingGroup', EntityType::class, [
                    'class' => HousingGroup::class,
                    'label' => 'Groupe de Logements',
                    'placeholder' => 'Veuillez sélectionner une ligne',
                    'query_builder' => function (EntityRepository $er) use ($lessor) {
                        return $er->createQueryBuilder('hg')
                            ->innerJoin('hg.lessor', 'l', Join::WITH, 'l.id = :lessorID')
                            ->setParameter('lessorID', $lessor->getId())
                        ;
                    },
                    ]);

            $builder
                ->add('type', ChoiceType::class, [
                    'label' => 'Type',
                    'choices' => Constants::getHousingTypes(),
                ])
                ->add('description', TextareaType::class, [
                    'label' => 'Description',
                    'required' => false,
                ])
                ->add('redirectLink', TextType::class, [
                    'label' => 'Lien de redirection',
                    'required' => true,
                    'help' => 'Lien vers lequel le visiteur sera redirigé en cliquant sur postuler',
                ])
                ->add('quantity', IntegerType::class, [
                    'label' => 'Nombre de logements',
                    'required' => true,
                    'help' => 'Nombre de logement de même type présents dans le groupe de logement',
                ])
                ->add('occupants', IntegerType::class, [
                    'label' => 'Nombre d\'occupants',
                    'required' => true,
                    'help' => 'dans un logement. 3 pour une colocation à 3 personnes',
                ])
                ->add('areaMin', IntegerType::class, [
                    'label' => 'Surface en m<sup>2</sup> (min)',
                    'label_html' => true,
                ])
                ->add('areaMax', IntegerType::class, [
                    'label' => 'Surface en m<sup>2</sup> (max)',
                    'label_html' => true,
                    'required' => false,
                ])
                ->add('rentMin', IntegerType::class, [
                    'label' => 'Loyer (min)',
                ])
                ->add('rentMax', IntegerType::class, [
                    'label' => 'Loyer (max)',
                    'required' => false,
                ])
                ->add('chargesIncluded', CheckboxType::class, [
                    'label' => 'Charges comprises',
                    'required' => false,
                ])
                ->add('chargesMin', IntegerType::class, [
                    'label' => 'Charges (min)',
                    'required' => false,
                ])
                ->add('chargesMax', IntegerType::class, [
                    'label' => 'Charges (max)',
                    'required' => false,
                ])
                ->add('available', CheckboxType::class, [
                    'label' => 'Disponible',
                    'required' => false,
                ])
                ->add('applicationFeeMin', IntegerType::class, [
                    'label' => 'Frais de dossier (min)',
                ])
                ->add('applicationFeeMax', IntegerType::class, [
                    'label' => 'Frais de dossier (max)',
                    'required' => false,
                ])
                ->add('securityDepositMin', IntegerType::class, [
                    'label' => 'Dépôt de garantie (min)',
                ])
                ->add('securityDepositMax', IntegerType::class, [
                    'label' => 'Dépôt de garantie (max)',
                    'required' => false,
                ])
                ->add('livingMode', ChoiceType::class, [
                    'label' => 'Mode d\'habitation',
                    'required' => true,
                    'choices' => Constants::getHousingLivingModes(),
                ])
                ->add('occupationModes', EntityType::class, [
                        'class' => OccupationMode::class,
                        'multiple' => true,
                        'expanded' => true,
                        'label' => 'Modes d\'occupation',
                    ])
                ->add('accessibility', CheckboxType::class, [
                    'label' => 'housing.accessible',
                    'required' => false,
                ])
                ->add('smoking', CheckboxType::class, [
                    'label' => 'housing.smoking',
                    'required' => false,
                ])
                ->add('animalsAllowed', CheckboxType::class, [
                    'label' => 'housing.animals_allowed',
                    'required' => false,
                ])
                ->add('aplAgreement', CheckboxType::class, [
                    'label' => 'housing.apl_agreement',
                    'required' => false,
                ])
                ->add('stayDurations', EntityType::class, [
                        'class' => StayDuration::class,
                        'multiple' => true,
                        'expanded' => true,
                        'label' => 'Durées de séjour possibles',
                    ])
                ->add('equipments', EntityType::class, [
                        'class' => Equipment::class,
                        'multiple' => true,
                        'expanded' => true,
                        'label_html' => true,
                        'choice_label' => function ($opt, $k, $v) {
                            return '<i class="'.$opt->getPicture().'"></i> '.$opt;
                        },
                        'choice_attr' => function ($key) use ($housingEquipments) {
                            // disabled if the search worked returned false
                            // beware : array_search can return meaning values ( 0 )
                            // that can be compared to false.. thus !==
                            $disabled = false !== array_search($key, $housingEquipments);

                            return $disabled ? ['disabled' => 'disabled'] : [];
                        },
                    ]);
        }

        if (HousingFormType::PHOTO == $options['form_part']) {
            $builder
                ->add('pictures', CollectionType::class, [
                    'entry_type' => HousingPictureFormType::class,
                    'allow_delete' => true,
                    'allow_add' => true,
                    'by_reference' => false,
                    'prototype' => true,
                    'entry_options' => ['label' => false],
                ]);
        }

        if (HousingFormType::SCHOOLCRITERION == $options['form_part']) {
            $builder
               ->add('schoolCriteria', CollectionType::class, [
                'entry_type' => SchoolCriterionFormType::class,
                'allow_delete' => true,
                'allow_add' => true,
                'by_reference' => false,
               ]);
        }

        if (HousingFormType::SOCIALSCHOLARSHIP == $options['form_part']) {
            $builder
               ->add('socialScholarshipCriteria', CollectionType::class, [
                'entry_type' => SocialScholarShipFormType::class,
                'allow_delete' => true,
                'allow_add' => true,
                'by_reference' => false,
               ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Housing::class,
            'form_part' => HousingFormType::PRINCIPAL,
        ]);
    }
}
