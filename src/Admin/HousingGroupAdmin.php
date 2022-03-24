<?php

namespace App\Admin;

use App\Constants;
use App\Entity\HousingGroup;
use App\Entity\Lessor;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class HousingGroupAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof HousingGroup ? (string) $object : 'Groupe de logements';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Général', [
                'class' => 'col-md-8 col-md-offset-2',
            ])
                ->add('name', TextType::class, [
                    'label' => 'Nom du groupe',
                ])
                ->add('lessor', ModelType::class, [
                    'label' => 'Bailleur',
                    'class' => Lessor::class,
                ])
            ->end()
            ->with('Adresse', [
                'class' => 'col-md-8 col-md-offset-2',
            ])
                ->add('address.street', TextType::class, [
                    'label' => 'Adresse (ligne 1)',
                ])
                ->add('address.streetDetail', TextType::class, [
                    'label' => 'Adresse (ligne 2)',
                    'required' => false,
                ])
                ->add('address.city', TextType::class, [
                    'label' => 'Ville',
                ])
                ->add('address.postalCode', TextType::class, [
                    'label' => 'Code postal',
                ])
                ->add('address.country', ChoiceType::class, [
                    'label' => 'Pays',
                    'choices' => Constants::getAddressCountries(),
                ])
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('name', null, [
            'show_filter' => true,
            'label' => 'Recherche par nom',
        ]);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('name', null, [
                'label' => 'Nom du groupe de logements',
            ])
            ->add('lessor.name', null, [
                'label' => 'Nom du bailleur',
            ])
            ->add('createdAt', 'datetime', [
                'format' => 'H:i:s -- d/m/Y',
                'label' => 'Création',
            ])
            ->add('updatedAt', 'datetime', [
                'format' => 'H:i:s -- d/m/Y',
                'label' => 'Dernière mise à jour',
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
            ->add('name', null, [
                'Nom du groupe de logements',
            ])
            ->add('lessor.name', null, [
                'Nom du bailleur',
            ])
            ->add('address.street', null, [
                'label' => 'Adresse (ligne 1)',
            ])
            ->add('address.streetDetail', null, [
                'label' => 'Adresse (ligne 2)',
            ])
            ->add('address.city', null, [
                'label' => 'Ville',
            ])
            ->add('address.postalCode', null, [
                'label' => 'Code postal',
            ])
            ->add('address.country', null, [
                'label' => 'Pays',
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
