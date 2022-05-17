<?php

namespace App\Admin;

use App\Entity\Housing;
use App\Entity\School;
use App\Entity\SchoolCriterion;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SchoolCriterionAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof SchoolCriterion ? (string) $object : 'Critère d\'accès établissements';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Général', [
                'class' => 'col-md-8 col-md-offset-2',
            ])
                ->add('startDate', DateType::class, [
                    'label' => 'Date de début',
                ])
                ->add('endDate', DateType::class, [
                    'label' => 'Date de fin',
                ]);

        if (!$this->isChild()) {
            $form
                ->add('housing', ModelType::class, [
                    'label' => 'Logement',
                    'class' => Housing::class,
                    'btn_add' => false,
                ]);
        }

        $form
            ->add('schools', ModelType::class, [
                'class' => School::class,
                'multiple' => true,
                'label' => 'Établissements concernés',
                'btn_add' => false,
            ])
            ->end();
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('startDate', 'datetime', [
                'label' => 'Date de début',
                'format' => 'd/m/Y',
            ])
            ->add('endDate', 'datetime', [
                'label' => 'Date de fin',
                'format' => 'd/m/Y',
            ]);

        if (!$this->isChild()) {
            $list
                ->addIdentifier('housing', null, [
                    'label' => 'Logement',
                ]);
        }
        $list
            ->add('schools', null, [
                'label' => 'Établissements concernés',
            ])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('startDate', 'datetime', [
                'label' => 'Date de début',
            ])
            ->add('endDate', 'datetime', [
                'label' => 'Date de fin',
            ])
            ->add('schools', null, [
                'label' => 'Établissements concernés',
            ])
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
