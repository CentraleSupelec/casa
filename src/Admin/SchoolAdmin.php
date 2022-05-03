<?php

declare(strict_types=1);

namespace App\Admin;

use App\Admin\Embed\AddressEmbeddedAdmin;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

final class SchoolAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('name')
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('name', null, [
                'label' => 'Nom',
                ])
            ->add('idGovernment', null, [
                'label' => 'Identifiant National',
                ])
            ->add('websiteUrl', null, [
                'label' => 'Adresse Internet',
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
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
           ->with('Général', [
                'class' => 'col-md-8 col-md-offset-2',
            ])
                ->add('name', TextType::class, [
                    'label' => 'Nom',
                ])
                ->add('idGovernment', TextType::class, [
                    'label' => 'Identifiant National',
                    'required' => false,
                ])
                ->add('websiteurl', UrlType::class, [
                    'label' => 'Adresse Internet',
                    'required' => false,
                ])
            ->end()
            ->with('Adresse', [
                'class' => 'col-md-8 col-md-offset-2',
            ]);

        AddressEmbeddedAdmin::addFormField($form);

        $form
            ->end()
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('name', null, [
                    'label' => 'Nom',
                ])
            ->add('idGovernment', null, [
                    'label' => 'Identifiant National',
                    'required' => false,
                ])
            ->add('websiteurl', null, [
                    'label' => 'Adresse Internet',
                    'required' => false,
            ]);

        AddressEmbeddedAdmin::addShowField($show);

        $show
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
