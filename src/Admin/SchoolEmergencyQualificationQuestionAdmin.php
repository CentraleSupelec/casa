<?php

namespace App\Admin;

use App\Entity\EmergencyQualificationQuestion;
use App\Entity\School;
use App\Entity\SchoolEmergencyQualificationQuestion;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class SchoolEmergencyQualificationQuestionAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof SchoolEmergencyQualificationQuestion
            ? (string) $object : 'Association établissement - question de qualification d\'urgence';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Général', [
                'class' => 'col-md-8 col-md-offset-2',
            ])
                ->add('question', ModelType::class, [
                    'label' => 'Question de qualification d\'urgence',
                    'class' => EmergencyQualificationQuestion::class,
                    'btn_add' => false,
                ])

                ->add('contactList', CollectionType::class, [
                    'label' => 'Contacts associés',
                    'entry_type' => EmailType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                ]);

        if (!$this->isChild()) {
            $form
                ->add('school', EntityType::class, [
                    'label' => 'Établissement',
                    'class' => School::class,
                    'btn_add' => false,
                ]);
        }

        $form
            ->end();
    }

    protected function configureListFields(ListMapper $list): void
    {
        if (!$this->isChild()) {
            $list->addIdentifier('school');
        }
        $list
            ->addIdentifier('question', null, [
                'label' => 'Question',
            ])
            ->add('contactList', null, [
                'label' => 'Contacts associés',
            ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('question', null, [
                'label' => 'Question',
            ])
            ->add('school', null, [
                'label' => 'Établissement',
            ])
            ->add('contactList', null, [
                'label' => 'Contacts associés',
            ]);
    }
}
