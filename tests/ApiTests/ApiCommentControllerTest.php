<?php

namespace App\Tests\ApiTests;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\String\ByteString;

class ApiCommentControllerTest extends ApiWebTestCase
{
    protected KernelBrowser $client;
    protected ?EntityManagerInterface $em;
    protected int $status = 200;
    protected array $postData = [];

    public function testApiCreateComment()
    {
        $userId = $this->user->getId();
        $employeeId = $this->employee->getId();
        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'user_id' => "$userId",
            'employee_id' => "$employeeId",
            'content' => ByteString::fromRandom(30)->toString(),
        ];

        $this->sendRequest('comment/create');
        $this->checkEqual($this->response('Comment created successfully'));
    }

    public function testApiUpdateComment()
    {
        $comment = $this->findComment();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'comment_id' => "$comment",
            'content' => ByteString::fromRandom(30)->toString(),
        ];

        $this->sendRequest('comment/update');
        $this->checkEqual($this->response('Comment updated successfully'));
    }

    public function testApiShowComment()
    {
        $comment = $this->findComment();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'comment_id' => "$comment",
        ];

        $this->sendRequest('comment/show');

        $this->assertResponseIsSuccessful();
        $this->assertEquals($this->status, $this->client->getResponse()->getStatusCode());
    }

    public function testApiDeleteComment()
    {
        $comment = $this->findComment();

        $this->postData = [
            'authorization_email' => $this->createUser()->getEmail(),
            'comment_id' => "$comment",
        ];

        $this->sendRequest('comment/delete');
        $this->checkEqual($this->response('Comment deleted successfully'));
    }

    protected function findComment()
    {
        return $this->em
            ->getRepository(Comment::class)
            ->findOneBy(
                [
                    'user_comment' => $this->user->getId(),
                    'employee' => $this->employee->getId(),
                ])
            ->getId();
    }
}
