<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Employee;
use App\Entity\Service;
use App\Entity\User;
use App\EventSubscriber\AppointmentSubscriber;
use App\Repository\ServicesRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\UuidType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BookingUserType extends AbstractType
{
    public function __construct(ManagerRegistry $entityManager, AuthorizationCheckerInterface $auth)
    {
        $this->entityManager = $entityManager;
        $this->auth = $auth;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('beginAt', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'required' => true,
            ])
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'required' => true,
                'placeholder' => 'Choose an option',
                'choice_label' => function ($category) {
                    return $category->getFirstName() . ' ' . $category->getLastName();
                }
            ])
            ->addEventSubscriber(new AppointmentSubscriber($this->entityManager))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'booking_class' => Booking::class,
        ]);
    }
}
