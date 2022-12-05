<?php

namespace App\Form\Lessor;

use App\Entity\School;
use App\Entity\SchoolCriterion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SchoolCriterionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', null, [
                 'widget' => 'single_text',
                 'label' => 'Debut de validité du critère',
                 ])
                 ->add('endDate', null, [
                     'widget' => 'single_text',
                     'label' => 'Fin de validité du critère',
            ])
            ->add('schools', EntityType::class, [
                'class' => School::class,
                'multiple' => true,
                'label' => 'Etablissements concernés',
                'help' => 'Selectionnez une pour plusieurs lignes à l\'aide de la touche ctrl ou command',
                'attr' => ['size' => 15],
                 'group_by' => function ($choice, $key, $value) {
                     return $choice->getParentSchool();
                 },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SchoolCriterion::class,
        ]);
    }
}
