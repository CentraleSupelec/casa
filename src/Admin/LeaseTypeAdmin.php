<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

final class LeaseTypeAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('sortOrder', null, [
                'label' => 'Tri',
            ])
            ->add('labelFr', null, [
                'label' => 'Libellé',
                ])
            ->add('labelEn', null, [
                'label' => 'Label ( english ) ',
            ])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('sortOrder', null, [
                'label' => 'Tri',
            ])
            ->add('labelFr', null, [
                'label' => 'Libellé',
                ])
            ->add('labelEn', null, [
                'label' => 'Label ( english ) ',
            ]);
    }
}
