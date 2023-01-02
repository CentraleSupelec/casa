<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Guarantor;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

final class GuarantorAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof Guarantor ? (string) $object : 'Guarant';
    }

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
            ])

        ;
    }
}
