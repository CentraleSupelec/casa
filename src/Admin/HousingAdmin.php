<?php

namespace App\Admin;

use App\Constants;
use App\Entity\Housing;
use App\Entity\HousingGroup;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class HousingAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof Housing ? (string) $object : 'Logement';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Général', [
                'class' => 'col-md-8 col-md-offset-2',
            ])
                ->add('housingGroup', ModelType::class, [
                    'class' => HousingGroup::class,
                ])
                ->add('type', ChoiceType::class, [
                    'label' => 'Type',
                    'choices' => Constants::getHousingTypes(),
                ])
                ->add('description', TextType::class, [
                    'label' => 'Description',
                    'required' => false,
                ])
                ->add('redirectLink', TextType::class, [
                    'label' => 'Lien de redirection',
                    'required' => false,
                ])
                ->add('areaMin', IntegerType::class, [
                    'label' => 'Surface (min)',
                ])
                ->add('areaMax', IntegerType::class, [
                    'label' => 'Surface (max)',
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
                    'required' => false,
                    'choices' => Constants::getHousingLivingModes(),
                ])
                ->add('occupationMode', ChoiceType::class, [
                    'label' => 'Mode d\'occupation',
                    'required' => false,
                    'choices' => Constants::getHousingOccupationModes(),
                ])
                ->add('accessibility', CheckboxType::class, [
                    'label' => 'Accessibilité PMR',
                    'required' => false,
                ])
                ->add('smoking', CheckboxType::class, [
                    'label' => 'Fumeur',
                    'required' => false,
                ])
                ->add('animalsAllowed', CheckboxType::class, [
                    'label' => 'Animaux autorisés',
                    'required' => false,
                ])
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('type', null, [
            'show_filter' => true,
            'label' => 'Recherche par type de logement',
        ]);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('housingGroup.name', null, [
                'label' => 'Groupe de logements',
            ])
            ->addIdentifier('type', null, [
                'label' => 'Type',
            ])
            ->add('areaMin', null, [
                'label' => 'Surface (min)',
            ])
            ->add('rentMin', null, [
                'label' => 'Loyer (min)',
            ])
            ->add('chargesIncluded', null, [
                'label' => 'Charges comprises',
            ])
            ->add('available', null, [
                'label' => 'Disponible',
            ])
            ->add('applicationFeeMin', null, [
                'label' => 'Frais de dossier (min)',
            ])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('housingGroup.name', null, [
                'label' => 'Groupe de logements',
            ])
            ->add('type', null, [
                'label' => 'Type',
            ])
            ->add('description', null, [
                'label' => 'Description',
            ])
            ->add('redirectLink', null, [
                'label' => 'Lien de redirection',
            ])
            ->add('areaMin', null, [
                'label' => 'Surface (min)',
            ])
            ->add('areaMax', null, [
                'label' => 'Surface (max)',
            ])
            ->add('rentMin', null, [
                'label' => 'Loyer (min)',
            ])
            ->add('rentMax', null, [
                'label' => 'Loyer (max)',
            ])
            ->add('chargesIncluded', null, [
                'label' => 'Charges comprises',
            ])
            ->add('chargesMin', null, [
                'label' => 'Charges (min)',
            ])
            ->add('chargesMax', null, [
                'label' => 'Charges (max)',
            ])
            ->add('available', null, [
                'label' => 'Disponible',
            ])
            ->add('applicationFeeMin', null, [
                'label' => 'Frais de dossier (min)',
            ])
            ->add('applicationFeeMax', null, [
                'label' => 'Frais de dossier (max)',
            ])
            ->add('securityDepositMin', null, [
                'label' => 'Dépôt de garantie (min)',
            ])
            ->add('securityDepositMax', null, [
                'label' => 'Dépôt de garantie (max)',
            ])
            ->add('livingMode', null, [
                'label' => 'Mode d\'habitation',
            ])
            ->add('occupationMode', null, [
                'label' => 'Mode d\'occupation',
            ])
            ->add('accessibility', null, [
                'label' => 'Accessibilité PMR',
            ])
            ->add('smoking', null, [
                'label' => 'Fumeur',
            ])
            ->add('animalsAllowed', null, [
                'label' => 'Animaux autorisés',
            ])
            ->add('createdAt', 'datetime', [
                'format' => 'H:i:s -- d/m/Y',
                'label' => 'Création',
            ])
            ->add('updatedAt', 'datetime', [
                'format' => 'H:i:s -- d/m/Y',
                'label' => 'Dernière mise à jour',
            ])
        ;
    }
}
