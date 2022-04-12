<?php

namespace App\Admin;

use App\Entity\HousingGroup;
use App\Entity\HousingGroupService;
use App\Entity\Service;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class HousingGroupServiceAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof HousingGroupService ? (string) $object : 'Association service - groupe de logements';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Général', [
                'class' => 'col-md-8 col-md-offset-2',
            ])
                ->add('service', ModelType::class, [
                    'label' => 'Désignation du service',
                    'class' => Service::class,
                    'btn_add' => false,
                ])

                ->add('isOptional', CheckboxType::class, [
                    'label' => 'Service en option (frais supplémentaires)',
                    'required' => false,
                ]);

        if (!$this->isChild()) {
            $form
                ->add('housingGroup', EntityType::class, [
                    'label' => 'Groupe de logements',
                    'class' => HousingGroup::class,
                ]);
        }

        $form
            ->end();
    }

    protected function configureListFields(ListMapper $list): void
    {
        if (!$this->isChild()) {
            $list->addIdentifier('housingGroup');
        }
        $list
            ->addIdentifier('service')
            ->add('isOptional', null, [
                'label' => 'Service en option',
            ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('service', null, [
                'label' => 'Désignation du service',
            ])
            ->add('housingGroup', null, [
                'label' => 'Groupe de logements',
            ])
            ->add('isOptional', null, [
                'label' => 'Service en option',
            ]);
    }
}
