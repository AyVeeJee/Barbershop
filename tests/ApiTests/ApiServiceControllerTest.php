<?php

namespace App\Tests\ApiTests;

use App\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\String\ByteString;

class ApiServiceControllerTest extends ApiWebTestCase
{
    protected KernelBrowser $client;
    protected ?EntityManagerInterface $em;
    protected int $status = 200;
    protected array $postData = [];

    public function testApiCreateService()
    {
        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'title' => $this->randomStr,
            'description' => $this->randomStr,
            'price' => '10',
        ];

        $this->sendRequest('service/create');
        $this->checkEqual($this->response('Service created successfully'));
    }

    public function testApiUpdateService()
    {
        $service = $this->findService();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'title' => $service->getTitle(),
            'description' => ByteString::fromRandom(30)->toString(),
        ];

        $this->sendRequest('service/update');
        $this->checkEqual($this->response('Service updated successfully'));
    }

    public function testApiShowService()
    {
        $service = $this->findService();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'title' => $service->getTitle(),
        ];

        $this->sendRequest('service/show');

        $this->assertResponseIsSuccessful();
        $this->assertEquals($this->status, $this->client->getResponse()->getStatusCode());
    }

    public function testApiDeleteService()
    {
        $service = $this->findService();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'title' => $service->getTitle(),
        ];

        $this->sendRequest('service/delete');
        $this->checkEqual($this->response('Service deleted successfully'));
    }

    protected function findService()
    {
        $record = $this->em
            ->getRepository(Service::class)
            ->findBy([], ['id'=>'DESC'],1,0);

        return reset($record);
    }
}
