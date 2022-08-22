<?php

namespace App\Controller\Api;

use DateTime;
use DateInterval;
use App\Entity\User;
use App\Entity\Booking;
use App\Entity\Service;
use App\Entity\Employee;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\Api\Requests\Booking\BookingShowRequest;
use App\Controller\Api\Requests\Booking\BookingDeleteRequest;
use App\Controller\Api\Requests\Booking\BookingCreateRequest;
use App\Controller\Api\Requests\Booking\BookingUpdateRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiBookingController extends AbstractController
{
    public function __construct(ManagerRegistry $entityManager)
    {
        $this->entityManager = $entityManager->getManager();
    }

    #[Route('/api/booking/create', name: 'api_create_booking', methods: ['POST'])]
    public function apiCreateBooking(BookingCreateRequest $request): JsonResponse
    {
        $request = $request->getRequest();

        $booking = new Booking();
        $this->createEntityFromRequest($booking, $request);

        $this->entityManager->persist($booking);
        $this->entityManager->flush();

        return $this->json([
            'status' => 200,
            'success' => "Booking created successfully",
        ]);
    }

    #[Route('/api/booking/update', name: 'api_update_booking', methods: ['POST'])]
    public function apiUpdateBooking(BookingUpdateRequest $request): JsonResponse
    {
        $request = $request->getRequest();
        $booking = $this->findBookingById($request);

        $this->createEntityFromRequest($booking, $request);
        $this->entityManager->flush();

        return $this->json([
            'status' => 200,
            'success' => "Booking updated successfully",
        ]);
    }

    #[Route('/api/booking/show', name: 'api_show_booking', methods: ['POST'])]
    public function apiShowBooking(BookingShowRequest $request): JsonResponse
    {
        $request = $request->getRequest();
        $booking = $this->findBookingByRequest($request);

        return $this->json([
            'status' => 200,
            'data' => $booking,
        ]);
    }

    #[Route('/api/booking/delete', name: 'api_delete_booking', methods: ['POST'])]
    public function apiDeleteBooking(BookingDeleteRequest $request): JsonResponse
    {
        $request = $request->getRequest();
        $booking = $this->findBookingById($request);

        $this->entityManager->remove($booking);
        $this->entityManager->flush();

        return $this->json([
            'status' => 200,
            'success' => "Booking deleted successfully",
        ]);
    }

    private function createEntityFromRequest(Booking $booking, $request): void
    {
        $user = $this->entityManager->find(User::class, $request->get('user_id', $booking->getAppointer()?->getId()));
        $employee = $this->entityManager->find(Employee::class, $request->get('employee_id', $booking->getEmployee()?->getId()));
        $service = $this->entityManager->find(Service::class, $request->get('service_id', $booking->getService()?->getId()));
        $begin_at = new DateTime($request->get('begin_at', $booking->getBeginAt()?->format('Y-m-d H:i:s')));

        $booking->setAppointer($user);
        $booking->setService($service);
        $booking->setEmployee($employee);
        $booking->setBeginAt(new DateTime($request->get('begin_at', $booking->getBeginAt()?->format('Y-m-d H:i:s'))));
        $booking->setEndAt($begin_at->add(new DateInterval('PT1H')));
    }

    private function findBookingById($request)
    {
        $booking = $this->entityManager->getRepository(Booking::class)->findOneBy(['id' => $request->get('booking_id')]);

        if (!$booking) {
            throw $this->createNotFoundException('No Booking found');
        }

        return $booking;
    }

    private function findBookingByRequest($request)
    {
        $booking = $this->entityManager->getRepository(Booking::class)->findByRequestApi($request);

        if (!$booking) {
            throw $this->createNotFoundException('No Booking found');
        }

        return $booking;
    }
}
