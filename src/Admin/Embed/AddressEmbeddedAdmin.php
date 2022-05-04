<?php

namespace App\Admin\Embed;

use App\Constants;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddressEmbeddedAdmin
{
    public static function addFormField(FormMapper $form): FormMapper
    {
        $form
        ->add('address.street', TextType::class, [
            'label' => 'Adresse (ligne 1)',
            ])
        ->add('address.streetDetail', TextType::class, [
            'label' => 'Adresse (ligne 2)',
            'required' => false,
            ])
        ->add('address.city', TextType::class, [
            'label' => 'Ville',
            ])
        ->add('address.postalCode', TextType::class, [
            'label' => 'Code postal',
            ])
        ->add('address.country', ChoiceType::class, [
            'label' => 'Pays',
            'choices' => Constants::getAddressCountries(),
            ])
        ->add('address.coordinates.latitude', NumberType::class, [
            'label' => 'Latitude',
            'scale' => 6,
            'required' => false,
            ])
        ->add('address.coordinates.longitude', NumberType::class, [
            'label' => 'Longitude',
            'scale' => 6,
            'required' => false,
            ]);

        return $form;
    }

    public static function addShowField(ShowMapper $show): ShowMapper
    {
        $show
            ->add('address.street', null, [
                'label' => 'Adresse (ligne 1)',
            ])
            ->add('address.streetDetail', null, [
                'label' => 'Adresse (ligne 2)',
            ])
            ->add('address.city', null, [
                'label' => 'Ville',
            ])
            ->add('address.postalCode', null, [
                'label' => 'Code postal',
            ])
            ->add('address.country', null, [
                'label' => 'Pays',
            ])
            ->add('address.coordinates', null, [
                'label' => 'Coordonnées',
            ]);

        return $show;
    }
}
