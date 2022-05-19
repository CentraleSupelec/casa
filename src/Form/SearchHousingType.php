<?php

namespace App\Form;

use App\Entity\HousingGroup;
use App\Model\SearchCriteriaModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchHousingType extends AbstractType
{
    public function __construct(private EntityManagerInterface $entitymanager)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // TODO : handle the maxResultsByPage parameter on the UI.

        $cities = $this->entitymanager->getRepository(HousingGroup::class)->getDistinctCities();
        // Add empty line to choices
        $cities[] = '';

        $builder
            ->add('maxPrice', IntegerType::class, [
                'label' => 'search.criteria.max_rent.label',
                'required' => false,
            ])
            ->add('minArea', IntegerType::class, [
                'label' => 'search.criteria.min_area.label',
                'required' => false,
                ])
            ->add('city', ChoiceType::class, [
                'label' => 'search.criteria.city.label',
                'choices' => $cities,
                'choice_label' => function ($choice, $key, $value) {
                    return $value;
                },
                ])
            ->add('accessibility', CheckboxType::class, [
                'label' => 'search.criteria.accessibility.label',
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
