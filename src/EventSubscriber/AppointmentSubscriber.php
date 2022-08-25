<?php

namespace App\EventSubscriber;

use App\Entity\Service;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class AppointmentSubscriber implements EventSubscriberInterface
{
    private ManagerRegistry $entityManager;

    public function __construct(ManagerRegistry $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
        ];
    }

    public function onPreSetData(FormEvent $event): FormInterface
    {
        $repository = $this->entityManager->getRepository(Service::class);

        $form = $event->getForm();
        $data = $event->getData();

        $employeeId = $data->getId();

        if ($data->getEmployee() !== null) {
            $employeeId = $data->getEmployee()->getId();
        }

        if (!$employeeId) {
            return $form->add('service', EntityType::class,
                [
                    'class' => Service::class,
                ]);
        }

        $services = $repository->findEmployee($employeeId);

        $array = [];
        foreach ($services as $service) {
            $array[$service['title']] = $this->entityManager->getManager()->find(Service::class, $service['id']);
        }

        $formOptions = [
            'required' => true,
            'empty_data' => '',
            'choices' => $array,
        ];

        return $form->add('service', ChoiceType::class, $formOptions);
    }
}
