<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'authentication.email.label',
                'attr' => [
                    'placeholder' => 'authentication.email.placeholder',
                ],
            ])
            ->add('plainPassword', PlainPasswordType::class)
        ;
    }
}
