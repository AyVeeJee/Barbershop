<?php

namespace App\Tests\ApiTests;

use App\Entity\Employee;
use App\Entity\Service;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\ByteString;

class ApiWebTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected ?EntityManagerInterface $em;
    protected int $status = 200;
    protected array $postData = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->randomStr = ByteString::fromRandom(6)->toString();
        $this->hasher = self::getContainer()->get('security.user_password_hasher');
        $this->em = self::getContainer()->get('doctrine.orm.entity_manager');

        $this->user = $this->createUser();
        $this->service = $this->createService();
        $this->employee = $this->createEmployee();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }

    protected function response($succesResponse): array
    {
        return [
            'status' => 200,
            'success' => $succesResponse,
        ];
    }

    protected function checkEqual($successfulResponse)
    {
        $this->assertResponseIsSuccessful();

        $this->assertEquals($this->status, $this->client->getResponse()->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $this->client->getResponse());
        $this->assertJsonStringEqualsJsonString(json_encode($successfulResponse), $this->client->getResponse()->getContent());
    }

    protected function sendRequest($method): Crawler
    {
        return $this->client->request(
            'POST',
            '/api/' . $method,
            $_POST = $this->postData,
        );
    }

    protected function createUser(): User
    {
        $record = $this->em
            ->getRepository(User::class)
            ->findByRole('ROLE_ADMIN');

        if (!empty($record)) {
            return reset($record);
        }

        $user = (new User())
            ->setLastName($this->randomStr)
            ->setFirstName($this->randomStr)
            ->setEmail(ByteString::fromRandom(6)->toString())
            ->setPhone($this->randomStr)
            ->setRoles(['ROLE_ADMIN']);

        $user->setPassword($this->hasher->hashPassword($user, $this->randomStr));

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    protected function createService(): Service
    {
        $record = $this->em
            ->getRepository(Service::class)
            ->findBy([], ['id'=>'DESC'],1,0);

        if (!empty($record)) {
            return reset($record);
        }

        $service = (new Service())
            ->setTitle('test')
            ->setDescription('test')
            ->setPrice(10);

        $this->em->persist($service);
        $this->em->flush();

        return $service;
    }

    protected function createEmployee(): Employee
    {
        $record = $this->em
            ->getRepository(Employee::class)
            ->findBy([], ['id'=>'DESC'],1,0);

        if (!empty($record)) {
            return reset($record);
        }

        $employee = (new Employee())
            ->setEmail('test@unittest.com')
            ->setPhone('+375255897887');

        $this->em->persist($employee);
        $this->em->flush();

        return $employee;
    }
}
