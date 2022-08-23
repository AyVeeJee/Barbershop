<?php

namespace App\Tests\ApiTests;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\String\ByteString;

class ApiEmployeeControllerTest extends ApiWebTestCase
{
    protected KernelBrowser $client;
    protected ?EntityManagerInterface $em;
    protected int $status = 200;
    protected array $postData = [];

    public function testApiCreateEmployee()
    {
        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'first_name' => $this->randomStr,
            'last_name' => $this->randomStr,
            'email' => $this->randomStr,
            'phone' => $this->randomStr,
        ];

        $this->sendRequest('employee/create');
        $this->checkEqual($this->response('Employee created successfully'));
    }

    public function testApiUpdateEmployee()
    {
        $employee = $this->findEmployee();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'email' => $employee->getEmail(),
            'last_name' => ByteString::fromRandom(30)->toString(),
        ];

        $this->sendRequest('employee/update');
        $this->checkEqual($this->response('Employee updated successfully'));
    }

    public function testApiShowEmployee()
    {
        $employee = $this->findEmployee();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'email' => $employee->getEmail(),
        ];

        $this->sendRequest('employee/show');

        $this->assertResponseIsSuccessful();
        $this->assertEquals($this->status, $this->client->getResponse()->getStatusCode());
    }

    public function testApiDeleteEmployee()
    {
        $employee = $this->findEmployee();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'email' => $employee->getEmail(),
        ];

        $this->sendRequest('employee/delete');
        $this->checkEqual($this->response('Employee deleted successfully'));
    }

    protected function findEmployee()
    {
        $record = $this->em
            ->getRepository(Employee::class)
            ->findBy([], ['id'=>'DESC'],1,0);

        return reset($record);
    }
}
