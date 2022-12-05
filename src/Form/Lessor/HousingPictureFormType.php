<?php

namespace App\Form\Lessor;

use App\Entity\HousingPicture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class HousingPictureFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', null, [
                'label' => 'Titre de la photo',
                'required' => false,
            ])

            ->add('file', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,

                'attr' => [
                    'class' => 'lessor-admin-image',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HousingPicture::class,
        ]);
    }
}
