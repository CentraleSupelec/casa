<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class HousingGenericRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('body', TextareaType::class, [
            'label' => 'housing_request.introduction',
            'row_attr' => ['class' => 'h-100'],
            'attr' => ['class' => 'h-75'],
            'trim' => false,
        ]);
    }
}
