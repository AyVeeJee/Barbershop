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
        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'user_id' => '1',
            'service_id' => '1',
            'employee_id' => '1',
            'begin_at' => $dataTime->format('d.m.Y h:i'),
        ];

        $this->sendRequest('booking/create');
        $this->checkEqual($this->response('Booking created successfully'));
    }

    public function testApiUpdateBooking()
    {
        $booking = $this->findBooking();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'booking_id' => "$booking",
            'service_id' => '1',
            'employee_id' => '1',
        ];

        $this->sendRequest('booking/update');
        $this->checkEqual($this->response('Booking updated successfully'));
    }

    public function testApiShowBooking()
    {
        $booking = $this->findBooking();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'booking_id' => "$booking",
        ];

        $this->sendRequest('booking/show');

        $this->assertResponseIsSuccessful();
        $this->assertEquals($this->status, $this->client->getResponse()->getStatusCode());
    }

    public function testApiDeleteBooking()
    {
        $booking = $this->findBooking();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'booking_id' => "$booking",
        ];

        $this->sendRequest('booking/delete');
        $this->checkEqual($this->response('Booking deleted successfully'));
    }

    protected function findBooking()
    {
        return $this->em
            ->getRepository(Booking::class)
            ->findOneBy(['appointer' => '1'])->getId();
    }
}
