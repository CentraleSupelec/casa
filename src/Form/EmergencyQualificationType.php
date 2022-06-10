<?php

namespace App\Form;

use App\Constants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class EmergencyQualificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach (Constants::getEmergencyQualificationQuestions() as $key => $value) {
            $builder->add($value, CheckboxType::class, [
                'label' => $key,
                'required' => false,
            ]);
        }
    }
}
