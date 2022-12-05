<?php

namespace App\Form\Lessor;

use App\Constants;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('street', null, [
                'label' => 'Rue',
            ])
            ->add('city', null, [
                'label' => 'Ville',
            ])
            ->add('postalcode', null, [
                'label' => 'Code postal', ])
            ->add('country', ChoiceType::class, [
                'choices' => Constants::getAddressCountries(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
