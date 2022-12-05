<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\StayDuration;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

final class StayDurationAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof StayDuration ? (string) $object : 'Durée de séjour';
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
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
            ->add('labelFr', null, [
                'label' => 'Libellé',
                ])
            ->add('labelEn', null, [
                'label' => 'Label ( english ) ',
            ]);
    }
}
