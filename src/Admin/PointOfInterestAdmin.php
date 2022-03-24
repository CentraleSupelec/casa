<?php

namespace App\Admin;

use App\Constants;
use App\Entity\HousingGroup;
use App\Entity\PointOfInterest;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PointOfInterestAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof PointOfInterest ? (string) $object : 'Point d\'intérêt';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Général', [
                'class' => 'col-md-8 col-md-offset-2',
            ])
                ->add('category', ChoiceType::class, [
                    'label' => 'Catégorie',
                    'choices' => Constants::getPointsOfInterestCategories(),
                ])
                ->add('label', TextType::class, [
                    'label' => 'Désignation',
                ])
                ->add('description', TextType::class, [
                    'label' => 'Description',
                    'required' => false,
                ])
                ->add('housingGroup', ModelType::class, [
                    'label' => 'Groupe de logements',
                    'class' => HousingGroup::class,
                    'btn_add' => false,
                ])
            ->end();
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('label', null, [
                'label' => 'Désignation',
            ])
            ->add('category', null, [
                'label' => 'Catégorie',
            ])
            ->addIdentifier('housingGroup', null, [
                'label' => 'Groupe de logements',
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
            ->add('label', null, [
                'label' => 'Désignation du service',
            ])
            ->add('category', null, [
                'label' => 'Catégorie',
            ])
            ->add('description', null, [
                'label' => 'Description',
            ])
            ->add('housingGroup', null, [
                'label' => 'Groupe de logements',
            ])
            ->add('created_at', 'datetime', [
                'format' => 'H:i:s -- d/m/Y',
                'label' => 'Création',
            ])
            ->add('updated_at', 'datetime', [
                'format' => 'H:i:s -- d/m/Y',
                'label' => 'Dernière mise à jour',
            ])
        ;
    }
}
