<?php

namespace App\Controller;

use App\Entity\Booking;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
class UserListAppointmentController extends AbstractController
{
    private ManagerRegistry $entityManager;

    public function __construct(ManagerRegistry $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/user/appointment', name: 'user_list_appointment')]
    public function index(): Response
    {
        $userId = $this->getUser()->getId();
        $bookingRepository = $this->entityManager->getManager()->getRepository(Booking::class);

        $bookings = $bookingRepository->findByUserId($userId);

        return $this->render('user_list_appointment/index.html.twig', [
            'bookings' => $bookings,
        ]);
    }

    #[Route('/user/appointment/delete', name: 'delete_user_appointment', methods: ['POST'])]
    public function deleteAppointment(Request $request): RedirectResponse
    {
        $bookingId = $request->get('booking_id');
        $userId = $this->getUser()->getId();

        $entityManager = $this->entityManager->getManager();
        $appointment = $entityManager->getRepository(Booking::class)->findOneBy(['appointer' => $userId, 'id' => $bookingId]);

        if (!$appointment) {
            throw $this->createNotFoundException(
                'No booking found for id '.$bookingId
            );
        }

        $entityManager->remove($appointment);
        $entityManager->flush();

        return $this->redirectToRoute('user_list_appointment');
    }
}
