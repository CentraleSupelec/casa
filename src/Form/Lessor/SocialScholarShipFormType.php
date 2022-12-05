<?php

namespace App\Form\Lessor;

use App\Entity\SocialScholarshipCriterion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocialScholarShipFormType extends AbstractType
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SocialScholarshipCriterion::class,
        ]);
    }
}
