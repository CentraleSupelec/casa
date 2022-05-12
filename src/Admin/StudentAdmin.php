<?php

declare(strict_types=1);

namespace App\Admin;

use App\Constants;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

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
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'choices' => Constants::getRoles(),
            ])
            ->add('enabled', BooleanType::class)
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('email', EmailType::class)
            ->add('roles')
            ->add('enabled')
            ->add('lastLoginAt')
            ->add('verificationToken')
            ->add('verified')
            ->add('createdAt')
            ->add('updatedAt')
            ;
    }
}
