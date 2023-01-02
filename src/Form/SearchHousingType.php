<?php

namespace App\Form;

use App\Entity\LeaseType;
use App\Entity\OccupationMode;
use App\Entity\StayDuration;
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

        $stayDurations = $this->entitymanager->getRepository(StayDuration::class)->findAll();
        $occupationModes = $this->entitymanager->getRepository(OccupationMode::class)->findAll();
        $leaseType = $this->entitymanager->getRepository(LeaseType::class)->findAll();

        $locale = $options['locale'];

        $builder
            ->add('maxPrice', IntegerType::class, [
                'label' => 'housing.search.criteria.max_rent.label',
                'required' => false,
                'attr' => ['min' => 0],
            ])
            ->add('minArea', IntegerType::class, [
                'label' => 'housing.search.criteria.min_area.label',
                'required' => false,
                'attr' => ['min' => 0],
                ])
            ->add('city', null, [
                'label' => 'housing.search.criteria.city.label',
                'attr' => [
                    'placeholder' => 'housing.search.criteria.city.placeholder',
                    'list' => 'citylistOptions',
                ],
                ])

            ->add('accessibility', CheckboxType::class, [
                'label' => 'housing.search.criteria.accessibility.label',
                'required' => false,
            ]);

        if ($options['advancedSearch']) {
            $builder
            ->add('stayDurations', ChoiceType::class, [
                'choices' => $stayDurations,
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label' => 'housing.search.criteria.length_stay.label',
                'choice_label' => function ($choice, $key, $value) use ($locale) {
                    return 'en' === $locale ? $choice->getLabelEn() : $choice->getLabelFr();
                },
                ])
            ->add('occupationModes', ChoiceType::class, [
                'choices' => $occupationModes,
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label' => 'housing.search.criteria.occupation_mode.label',
                'choice_label' => function ($choice, $key, $value) use ($locale) {
                    return 'en' === $locale ? $choice->getLabelEn() : $choice->getLabelFr();
                },
                ])
            ->add('leaseType', ChoiceType::class, [
                'choices' => $leaseType,
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label' => 'housing.search.criteria.lease_type.label',
                'choice_label' => function ($choice, $key, $value) use ($locale) {
                    return 'en' === $locale ? $choice->getLabelEn() : $choice->getLabelFr();
                },
                ])

            ->add('aplAgreement', CheckboxType::class, [
                'label' => 'housing.search.criteria.apl_agreement.label',
                'required' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchCriteriaModel::class,
            'locale' => 'fr',
            'advancedSearch' => false,
        ]);
    }
}
