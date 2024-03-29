<?php

namespace App\Admin;

use App\Constants;
use App\Entity\Equipment;
use App\Entity\Housing;
use App\Entity\HousingGroup;
use App\Entity\OccupationMode;
use App\Entity\StayDuration;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class HousingAdmin extends AbstractAdmin
{
    protected function configureTabMenu(MenuItemInterface $menu, string $action, ?AdminInterface $childAdmin = null): void
    {
        if (!$childAdmin && !in_array($action, ['edit', 'show'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');

        $menu->addChild('View', $admin->generateMenuUrl('show', ['id' => $id]));

        if ($this->isGranted('EDIT')) {
            $menu->addChild('Edit', $admin->generateMenuUrl('edit', ['id' => $id]));
        }

        if ($this->isGranted('LIST')) {
            $menu->addChild('Photos', $admin->generateMenuUrl('admin.housing_picture.list', ['id' => $id]));
            $menu->addChild(
                'Accès boursiers',
                $admin->generateMenuUrl('admin.social_scholarship_criterion.list', ['id' => $id])
            );
            $menu->addChild(
                'Accès établissements',
                $admin->generateMenuUrl('admin.school_criterion.list', ['id' => $id])
            );
        }
    }

    public function toString(object $object): string
    {
        return $object instanceof Housing ? (string) $object : 'Logement';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Général', [
                'class' => 'col-md-8 col-md-offset-2',
            ]);

        if (!$this->isChild()) {
            $form
                ->add('housingGroup', ModelType::class, [
                    'class' => HousingGroup::class,
                ]);
        }

        $form
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
                'required' => false,
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Nombre de logements',
                'required' => false,
            ])
            ->add('occupants', IntegerType::class, [
                'label' => 'Nombre d\'occupants',
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
                'required' => true,
                'choices' => Constants::getHousingLivingModes(),
            ])
            ->add('occupationModes', ModelType::class, [
                'class' => OccupationMode::class,
                'multiple' => true,
                'label' => 'Modes d\'occupation',
                'btn_add' => false,
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
            ->end()
            ->with('Durée de séjour', [
                'class' => 'col-md-8 col-md-offset-2',
            ])
                ->add('staydurations', ModelType::class, [
                    'class' => StayDuration::class,
                    'multiple' => true,
                    'label' => 'Durées de séjour possibles pour le logement',
                    'btn_add' => false,
                ])
            ->end()
            ->with('Équipements', [
                'class' => 'col-md-8 col-md-offset-2',
            ])
                ->add('equipments', ModelType::class, [
                    'class' => Equipment::class,
                    'multiple' => true,
                    'label' => 'Équipements des logements',
                    'btn_add' => false,
                ])
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
                ->add('type', ChoiceFilter::class, [
                    'show_filter' => true,
                    'label' => 'Recherche par type de logement',
                    'field_type' => ChoiceType::class,
                    'field_options' => [
                        'choices' => Constants::getHousingTypes(),
                    ],
                ])
                ->add('housingGroup', null, [
                    'show_filter' => true,
                    'label' => 'Recherche par Groupe de Logement',
                    ])

            ;
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
            ->add('quantity', null, [
                'label' => 'Nombre de logements',
            ])
            ->add('occupants', null, [
                'label' => 'Nombre d\'occupants',
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
            ]);
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
            ->add('quantity', null, [
                'label' => 'Nombre de logements',
            ])
            ->add('occupants', null, [
                'label' => 'Nombre d\'occupants',
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
            ->add('occupationModes', null, [
                'label' => 'Mode d\'occupation',
            ])
            ->add('aplAgreement', null, [
                'label' => 'housing.apl_agreement',
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
            ]);
    }
}
