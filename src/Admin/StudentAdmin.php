<?php

declare(strict_types=1);

namespace App\Admin;

use App\Constants;
use App\Entity\School;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class StudentAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->remove('create');
        $collection->remove('delete');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('roles')
            ->add('enabled')
            ->add('lastLoginAt')
            ->add('verified')
            ->add('createdAt')
            ->add('updatedAt')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Données personnelles')
                ->add('firstName', TextType::class)
                ->add('lastName', TextType::class)
                ->add('phone', TelType::class)
                ->add('birthdate', DateType::class,
                    ['widget' => 'single_text'])
                ->add('school', EntityType::class, [
                    'class' => School::class,
                ])
                ->add('socialScholarship', CheckboxType::class, [
                        'label' => 'Boursier',
                        'required' => false,
                    ])
                ->add('reducedMobility', CheckboxType::class, [
                        'label' => 'Personne à Mobilité réduite',
                        'required' => false,
                    ])
            ->end()
            ->with('Données du compte')
                    ->add('roles', ChoiceType::class, [
                    'multiple' => true,
                    'choices' => Constants::getRoles(),
                ])
                ->add('enabled', BooleanType::class)
            ->end()
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->with('Données personnelles')
                ->add('email')
                ->add('firstName')
                ->add('lastName')
                ->add('phone')
                ->add('birthdate', null,
                    ['widget' => 'single_text'])
                ->add('school')
                ->add('socialScholarship', null, [
                        'label' => 'Boursier',
                        'required' => false,
                    ])
                ->add('reducedMobility', null, [
                        'label' => 'Personne à Mobilité réduite',
                        'required' => false,
                    ])
            ->end()
            ->with('Données du compte')
                ->add('roles')
                ->add('enabled')
                ->add('lastLoginAt')
                ->add('verificationToken')
                ->add('verified')
                ->add('createdAt')
                ->add('updatedAt')
            ->end()
            ;
    }
}
