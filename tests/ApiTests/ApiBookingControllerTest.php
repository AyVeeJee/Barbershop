<?php

namespace App\Tests\ApiTests;

use App\Entity\Booking;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ApiBookingControllerTest extends ApiWebTestCase
{
    protected KernelBrowser $client;
    protected ?EntityManagerInterface $em;
    protected int $status = 200;
    protected array $postData = [];

    public function testApiCreateBooking()
    {
        $dataTime = new \DateTime();
        $userId = $this->user->getId();
        $serviceId = $this->service->getId();
        $employeeId = $this->employee->getId();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'user_id' => "$userId",
            'service_id' => "$serviceId",
            'employee_id' => "$employeeId",
            'begin_at' => $dataTime->format('d.m.Y h:i'),
        ];

        $this->sendRequest('booking/create');
        $this->checkEqual($this->response('Booking created successfully'));
    }

    public function testApiUpdateBooking()
    {
        $serviceId = $this->service->getId();
        $employeeId = $this->employee->getId();
        $bookingId = $this->findBooking();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'booking_id' => "$bookingId",
            'service_id' => "$serviceId",
            'employee_id' => "$employeeId",
        ];

        $this->sendRequest('booking/update');
        $this->checkEqual($this->response('Booking updated successfully'));
    }

    public function testApiShowBooking()
    {
        $bookingId = $this->findBooking();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'booking_id' => "$bookingId",
        ];

        $this->sendRequest('booking/show');

        $this->assertResponseIsSuccessful();
        $this->assertEquals($this->status, $this->client->getResponse()->getStatusCode());
    }

    public function testApiDeleteBooking()
    {
        $bookingId = $this->findBooking();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'booking_id' => "$bookingId",
        ];

        $this->sendRequest('booking/delete');
        $this->checkEqual($this->response('Booking deleted successfully'));
    }

    protected function findBooking()
    {
        return $this->em
            ->getRepository(Booking::class)
            ->findOneBy(
                [
                    'appointer' => $this->user->getId(),
                    'service' => $this->service->getId(),
                    'employee' => $this->employee->getId(),
                ])
            ->getId();
    }
}
