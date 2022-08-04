<?php

namespace App\Controller;

use App\Controller\Admin\BookingCrudController;
use App\Entity\Booking;
use App\Entity\Employee;
use App\Form\BookingAdminType;
use App\Form\BookingUserType;
use App\Repository\BookingRepository;
use DateInterval;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewAppointment extends AbstractController
{
    public function __construct(AdminUrlGenerator $crudUrlGenerator, BookingRepository $bookingRepository, ManagerRegistry $entityManager)
    {
        $this->crudUrlGenerator = $crudUrlGenerator;
        $this->bookingRepository = $bookingRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/booking", name="admin_booking")
     */
    public function newAdminAppointment(Request $request): Response
    {
        $booking = new Booking();

        $form = $this->createForm(BookingAdminType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookingRepository->add($booking, true);

            $url = $this->crudUrlGenerator
                ->setController(BookingCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl();

            return $this->redirect($url);
        }

        return $this->renderForm('admin/booking.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    #[Route('/admin/booking/show', name: 'app_booking_show', methods: ['GET'])]
    public function show(Request $request): Response
    {
        $repository = $this->entityManager->getManager()->getRepository(Booking::class);
        $booking = $repository->findOneBy(['id' => $request->get('id')]);

        return $this->render('admin/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    #[Route('/booking', name: 'user_booking')]
    public function newUserAppointment(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $employeeId = $request->get('booking_employee');
        $booking = new Booking();

        if ($employeeId !== null) {
            $repository = $this->entityManager->getManager()->getRepository(Employee::class);
            $employee = $repository->findOneBy(['id' => $employeeId]);

            $booking->setEmployee($employee);
        }

        $form = $this->createForm(BookingUserType::class, $booking);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $booking->setAppointer($this->getUser());
            $endAt = new DateTime($booking->getBeginAt()->format('Y-m-d H:i:s'));
            $booking->setEndAt($endAt->add(new DateInterval('PT1H')));

            $this->bookingRepository->add($booking, true);

            return $this->redirectToRoute('main_page');
        }

        return $this->renderForm('user/booking.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/appointment/admin/ajax", name="admin_appointment")
     */
    public function ajaxAdminAction(Request $request)
    {
        $booking = new Booking();

        $booking->setId($request->get('booking_employee'));
        $form = $this->createForm(BookingAdminType::class, $booking);

        return $this->renderForm('admin/booking.appointment.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/appointment/user/ajax", name="user_appointment")
     */
    public function ajaxUserAction(Request $request)
    {
        $booking = new Booking();

        $booking->setId($request->get('booking_employee'));
        $form = $this->createForm(BookingUserType::class, $booking);

        return $this->renderForm('user/booking.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }
}