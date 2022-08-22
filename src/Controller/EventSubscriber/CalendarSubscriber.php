<?php

namespace App\Controller\EventSubscriber;

use App\Repository\BookingRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $bookingRepository;
    private $router;

    public function __construct(
        BookingRepository     $bookingRepository,
        UrlGeneratorInterface $router
    )
    {
        $this->bookingRepository = $bookingRepository;
        $this->router = $router;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        $bookings = $this->bookingRepository
            ->createQueryBuilder('b')
            ->leftJoin('b.employee', 'e')
            ->leftJoin('b.service', 's')
            ->andWhere('b.beginAt BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();

        if ($filters['id'] !== null) {
            $bookings = $this->bookingRepository->findByEmployeeId($start, $end, $filters);
        }

        foreach ($bookings as $booking) {
            $bookingEvent = new Event(
                $booking->getEmployee()->getFirstName() . ' ' . $booking->getEmployee()->getLastName() . ' ' . $booking->getService()->getService(),
                $booking->getBeginAt(),
                $booking->getEndAt()
            );

            $bookingEvent->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'green',
            ]);
//            $bookingEvent->addOption(
//                'url',
//                $this->router->generate('app_booking_show', [
//                    'id' => $booking->getId(),
//                ])
//            );

            $calendar->addEvent($bookingEvent);
        }
    }
}