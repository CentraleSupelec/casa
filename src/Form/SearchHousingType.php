<?php

namespace App\Form;

use App\Model\SearchCriteriaModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchHousingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // TODO : handle the maxResultsByPage parameter on the UI.

        $builder
            ->add('maxPrice', IntegerType::class, [
                'label' => 'search.criteria.max_rent.label',
                'required' => false,
            ])
            ->add('minArea', IntegerType::class, [
                'label' => 'search.criteria.min_area.label',
                'required' => false,
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchCriteriaModel::class,
        ]);
    }
}
