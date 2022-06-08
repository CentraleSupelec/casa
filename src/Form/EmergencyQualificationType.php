<?php

namespace App\Form;

use App\Entity\EmergencyQualificationQuestion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmergencyQualificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var EmergencyQualificationQuestion $question */
        foreach ($options['qualification_questions'] as $question) {
            $builder->add('question_'.$question->getId(), CheckboxType::class, [
                'label' => 'housing_request.emergency.qualification.questions.'.$question->getTranslationLabel(),
                'required' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('qualification_questions');
        $resolver->setAllowedTypes(
            'qualification_questions',
            'App\Entity\EmergencyQualificationQuestion[]'
        );
    }
}
