<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

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
            ->add('plainPassword', PasswordType::class, [
                'label' => 'authentication.password.label',
                'attr' => ['placeholder' => 'authentication.password.placeholder'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'authentication.password.blank',
                    ]),
                    new Length([
                        'min' => 6,
                        'max' => 4096,
                        'minMessage' => 'authentication.password.short',
                        'maxMessage' => 'authentication.password.long',
                    ]),
                ],
            ])
        ;
    }
}
