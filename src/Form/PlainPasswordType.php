<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PlainPasswordType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'type' => PasswordType::class,
            'first_options' => [
                'label' => 'authentication.password.label',
                'attr' => ['placeholder' => 'authentication.password.placeholder'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'authentication.password.blank',
                    ]),
                    new Length([
                        'min' => 8,
                        'max' => 4096,
                        'minMessage' => 'authentication.password.short',
                        'maxMessage' => 'authentication.password.long',
                    ]),
                ],
            ],
            'second_options' => [
                'attr' => [
                    'placeholder' => 'authentication.password.placeholder',
                ],
                'label' => 'authentication.password.repeat',
            ],
        ]);
    }

    public function getParent(): string
    {
        return RepeatedType::class;
    }
}
