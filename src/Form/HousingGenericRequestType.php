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
            'label' => 'housing_request.common.introduction',
            'label_attr' => ['class' => 'fs-5'],
            'attr' => [
                'class' => 'contact-form-textarea',
                'placeholder' => 'housing_request.emergency.hints.location', ],
            'trim' => false,
        ]);
    }
}
