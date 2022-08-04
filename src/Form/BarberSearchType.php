<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BarberSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', ChoiceType::class)
            ->add('services', EntityType::class, [
                'class' => Service::class,
                'required' => true,
                'placeholder' => 'All',
                'choice_label' => function ($service) {
                    return $service->getService();
                }
            ])
            ->add('last_name')
            ->add('service')
            ->add('email')
            ->add('phone')
            ->add('services')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
