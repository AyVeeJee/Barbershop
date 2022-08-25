<?php

namespace App\Tests\ApiTests;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ApiEmployeeControllerTest extends ApiWebTestCase
{
    protected KernelBrowser $client;
    protected ?EntityManagerInterface $em;
    protected int $status = 200;
    protected array $postData = [];
    /**
     * @var Employee|Employee&MockObject|MockObject
     */
    private MockObject|Employee $employeeMock;

    public function __construct()
    {
        parent::__construct();

        $this->employeeMock = $this->createConfiguredMock(Employee::class,
            [
                'getFirstName' => 'test',
                'getLastName' => 'test',
                'getEmail' => 'test@test.com',
                'getPhone' => '+375293458798',
            ]);
    }

    public function testApiCreateEmployee()
    {
        $firstName = $this->employeeMock->getFirstName();
        $lastName = $this->employeeMock->getLastName();
        $email = $this->employeeMock->getEmail();
        $phone = $this->employeeMock->getPhone();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'first_name' => "$firstName",
            'last_name' => "$lastName",
            'email' => "$email",
            'phone' => "$phone",
        ];

        $this->sendRequest('employee/create');
        $this->checkEqual($this->response('Employee created successfully'));
    }

    public function testApiUpdateEmployee()
    {
        $lastName = $this->employeeMock->getLastName();
        $email = $this->employeeMock->getEmail();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'email' => "$email",
            'last_name' => "$lastName",
        ];

        $this->sendRequest('employee/update');
        $this->checkEqual($this->response('Employee updated successfully'));
    }

    public function testApiShowEmployee()
    {
        $email = $this->employeeMock->getEmail();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'email' => "$email",
        ];

        $this->sendRequest('employee/show');

        $this->assertResponseIsSuccessful();
        $this->assertEquals($this->status, $this->client->getResponse()->getStatusCode());
    }

    public function testApiDeleteEmployee()
    {
        $email = $this->employeeMock->getEmail();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'email' => "$email",
        ];

        $this->sendRequest('employee/delete');
        $this->checkEqual($this->response('Employee deleted successfully'));
    }
}
