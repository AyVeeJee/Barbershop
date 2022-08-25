<?php

namespace App\Tests\ApiTests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\String\ByteString;

class ApiUserControllerTest extends ApiWebTestCase
{
    protected KernelBrowser $client;
    protected ?EntityManagerInterface $em;
    protected int $status = 200;
    protected array $postData = [];
    private User&MockObject $userMock;

    public function __construct()
    {
        parent::__construct();

        $this->userMock = $this->createConfiguredMock(User::class,
            [
                'getPassword' => ByteString::fromRandom(6)->toString(),
                'getLastName' => 'test',
                'getEmail' => 'test@test.com',
                'getPhone' => '+375293458798',
            ]);
    }

    public function testApiCreateUser()
    {
        $email = $this->userMock->getEmail();
        $password = $this->userMock->getPassword();
        $phone = $this->userMock->getPhone();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'email' => "$email",
            'password' => "$password",
            'phone' => "$phone",
        ];

        $this->sendRequest('user/create');
        $this->checkEqual($this->response('User created successfully'));
    }

    public function testApiUpdateUser()
    {
        $email = $this->user->getEmail();
        $password = $this->user->getPassword();
        $lastName = $this->user->getLastName();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'email' => "$email",
            'last_name' => "$lastName",
            'password' => "$password",
        ];

        $this->sendRequest('user/update');
        $this->checkEqual($this->response('User updated successfully'));
    }

    public function testApiShowUser()
    {
        $email = $this->user->getEmail();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'email' => "$email",
        ];

        $this->sendRequest('user/show');

        $this->assertResponseIsSuccessful();
        $this->assertEquals($this->status, $this->client->getResponse()->getStatusCode());
    }

    public function testApiDeleteUser()
    {
        $email = $this->userMock->getEmail();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'email' => "$email",
        ];

        $this->sendRequest('user/delete');
        $this->checkEqual($this->response('User deleted successfully'));
    }
}
