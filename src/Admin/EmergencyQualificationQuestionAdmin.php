<?php

namespace App\Admin;

use App\Entity\EmergencyQualificationQuestion;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EmergencyQualificationQuestionAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof EmergencyQualificationQuestion ?
            (string) $object : 'Question de qualification d\'urgence';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Général', [
                'class' => 'col-md-8 col-md-offset-2',
            ])
                ->add('translationLabel', TextType::class, [
                    'label' => 'Code de traduction de la question',
                ])
                ->add('description', TextType::class, [
                    'label' => 'Description de la question',
                    'required' => false,
                ])
            ->end();
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('translationLabel', null, [
                'label' => 'Code de traduction de la question',
            ])
            ->add('description', null, [
                'label' => 'Description',
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
            ->add('translationLabel', null, [
                'label' => 'Code de traduction de la question',
            ])
            ->add('description', null, [
                'label' => 'Description',
            ])
        ;
    }
}
