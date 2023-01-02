<?php

namespace App\Form;

use App\Model\SearchHousingGroupCriteriaModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchHousingGroupType extends AbstractType
{
    public function __construct(private EntityManagerInterface $entitymanager)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // TODO : handle the maxResultsByPage parameter on the UI.

        $builder
            ->add('city', null, [
                'label' => 'housing.search.criteria.city.label',
                'attr' => [
                    'placeholder' => 'housing.search.criteria.city.placeholder',
                    'list' => 'citylistOptions',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchHousingGroupCriteriaModel::class,
        ]);
    }
}
