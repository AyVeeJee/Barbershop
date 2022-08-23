<?php

namespace App\Tests\ApiTests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ApiUserControllerTest extends ApiWebTestCase
{
    protected KernelBrowser $client;
    protected ?EntityManagerInterface $em;
    protected int $status = 200;
    protected array $postData = [];

    public function testApiCreateUser()
    {
        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'email' => $this->randomStr,
            'password' => $this->randomStr,
            'phone' => '10',
        ];

        $this->sendRequest('user/create');
        $this->checkEqual($this->response('User created successfully'));
    }

    public function testApiUpdateUser()
    {
        $user = $this->findUser();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'email' => $user->getEmail(),
            'last_name' => $this->randomStr,
            'password' => $this->randomStr,
        ];

        $this->sendRequest('user/update');
        $this->checkEqual($this->response('User updated successfully'));
    }

    public function testApiShowUser()
    {
        $user = $this->findUser();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'email' => $user->getEmail(),
        ];

        $this->sendRequest('user/show');

        $this->assertResponseIsSuccessful();
        $this->assertEquals($this->status, $this->client->getResponse()->getStatusCode());
    }

    public function testApiDeleteUser()
    {
        $user = $this->findUser();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'email' => $user->getEmail(),
        ];

        $this->sendRequest('user/delete');
        $this->checkEqual($this->response('User deleted successfully'));
    }

    protected function findUser()
    {
        $record = $this->em
            ->getRepository(User::class)
            ->findBy([], ['id'=>'DESC'],1,0);

        return reset($record);
    }
}
