<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Employee;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\Api\Requests\Comment\CommentShowRequest;
use App\Controller\Api\Requests\Comment\CommentDeleteRequest;
use App\Controller\Api\Requests\Comment\CommentCreateRequest;
use App\Controller\Api\Requests\Comment\CommentUpdateRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiCommentController extends AbstractController
{
    public function __construct(ManagerRegistry $entityManager)
    {
        $this->entityManager = $entityManager->getManager();
    }

    #[Route('/api/comment/create', name: 'api_create_comment', methods: ['POST'])]
    public function apiCreateComment(CommentCreateRequest $request): JsonResponse
    {
        $comment = new Comment();
        $request = $request->getRequest();

        $this->createEntityFromRequest($comment, $request);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return $this->json([
            'status' => 200,
            'success' => "Comment created successfully",
        ]);
    }

    #[Route('/api/comment/update', name: 'api_update_comment', methods: ['POST'])]
    public function apiUpdateComment(CommentUpdateRequest $request): JsonResponse
    {
        $request = $request->getRequest();
        $comment = $this->findCommentById($request);

        $this->createEntityFromRequest($comment, $request);
        $this->entityManager->flush();

        return $this->json([
            'status' => 200,
            'success' => "Comment updated successfully",
        ]);
    }

    #[Route('/api/comment/show', name: 'api_show_comment', methods: ['POST'])]
    public function apiShowComment(CommentShowRequest $request): JsonResponse
    {
        $request = $request->getRequest();
        $comment = $this->findCommentByRequest($request);

        return $this->json([
            'status' => 200,
            'data' => $comment,
        ]);
    }

    #[Route('/api/comment/delete', name: 'api_delete_comment', methods: ['POST'])]
    public function apiDeleteComment(CommentDeleteRequest $request): JsonResponse
    {
        $request = $request->getRequest();
        $comment = $this->findCommentById($request);

        $this->entityManager->remove($comment);
        $this->entityManager->flush();

        return $this->json([
            'status' => 200,
            'success' => "Comment deleted successfully",
        ]);
    }

    private function createEntityFromRequest(Comment $comment, $request): void
    {
        $user = $this->entityManager->find(User::class, $request->get('user_id', $comment->getUserComment()?->getId()));
        $employee = $this->entityManager->find(Employee::class, $request->get('employee_id', $comment->getEmployee()?->getId()));
        $content = $request->get('content', $comment->getContent());

        $comment->setUserComment($user);
        $comment->setContent($content);
        $comment->setEmployee($employee);
    }

    private function findCommentById($request)
    {
        $comment = $this->entityManager->getRepository(Comment::class)->findOneBy(['id' => $request->get('comment_id')]);

        if (!$comment) {
            throw $this->createNotFoundException('No Comment found');
        }

        return $comment;
    }

    private function findCommentByRequest($request)
    {
        $comment = $this->entityManager->getRepository(Comment::class)->findByRequestApi($request);

        if (!$comment) {
            throw $this->createNotFoundException('No Comment found');
        }

        return $comment;
    }
}
