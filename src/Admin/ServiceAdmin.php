<?php

namespace App\Admin;

use App\Entity\Service;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ServiceAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof Service ? (string) $object : 'Service';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Général', [
                'class' => 'col-md-8 col-md-offset-2',
            ])
                ->add('label', TextType::class, [
                    'label' => 'Désignation du service',
                ])
                ->add('picture', TextType::class, [
                    'label' => 'Code icone',
                ])
            ->end();
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('label', null, [
                'label' => 'Désignation',
                ])
            ->add('picture', TextType::class, [
                'label' => 'Code icone',
            ])
            ->add('created_at', 'datetime', [
                'format' => 'H:i:s -- d/m/Y',
                'label' => 'Création',
            ])
            ->add('updated_at', 'datetime', [
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
            ->add('label', null, [
                'label' => 'Désignation du service',
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
