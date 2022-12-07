<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class PlainPasswordType extends AbstractType
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $minLimit = 8;
        $maxLimit = 4096;

        $minMessage = $this->translator->trans('authentication.password.short', ['%limit%' => $minLimit]);
        $maxMessage = $this->translator->trans('authentication.password.long', ['%limit' => $maxLimit]);

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
                        'min' => $minLimit,
                        'max' => $maxLimit,
                        'minMessage' => $minMessage,
                        'maxMessage' => $maxMessage,
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
