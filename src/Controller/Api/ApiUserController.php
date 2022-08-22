<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Api\Requests\User\UserUpdateRequest;
use App\Controller\Api\Requests\User\UserCreateRequest;
use App\Controller\Api\Requests\User\UserShowAndDeleteRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApiUserController extends AbstractController
{
    public function __construct(UserPasswordHasherInterface $passwordEncoder, ManagerRegistry $entityManager)
    {
        $this->entityManager = $entityManager->getManager();
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route('/api/user/create', name: 'api_create_user', methods: ['POST'])]
    public function apiCreateUser(UserCreateRequest $request): JsonResponse
    {
        $user = new User();
        $request = $request->getRequest();

        $this->createEntityFromRequest($user, $request);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'status' => 200,
            'success' => "User created successfully",
        ]);
    }

    #[Route('/api/user/update', name: 'api_update_user', methods: ['POST'])]
    public function apiUpdateUser(UserUpdateRequest $request): JsonResponse
    {
        $request = $request->getRequest();
        $user = $this->findUserByEmail($request);

        $this->createEntityFromRequest($user, $request);
        $this->entityManager->flush();

        return $this->json([
            'status' => 200,
            'success' => "User updated successfully",
        ]);
    }

    #[Route('/api/user/show', name: 'api_show_user', methods: ['POST'])]
    public function apiShowUser(UserShowAndDeleteRequest $request): JsonResponse
    {
        $request = $request->getRequest();
        $user = $this->findUserByEmail($request);

        return $this->json([
            'status' => 200,
            'data' => $user,
        ]);
    }

    #[Route('/api/user/delete', name: 'api_delete_user', methods: ['POST'])]
    public function apiDeleteUser(UserShowAndDeleteRequest $request): JsonResponse
    {
        $request = $request->getRequest();
        $user = $this->findUserByEmail($request);

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->json([
            'status' => 200,
            'success' => "User deleted successfully",
        ]);
    }

    private function createEntityFromRequest(User $user, $request): void
    {
        $user->setFirstName($request->get('first_name', $user->getFirstName()));
        $user->setLastName($request->get('last_name', $user->getLastName()));
        $user->setEmail($request->get('email', $user->getEmail()));
        if ($request->get('password')) {
            $user->setPassword($this->passwordEncoder->hashPassword($user, $request->get('password')));
        }
        $user->setPhone($request->get('phone', $user->getPhone()));
        $user->setRoles($user->getRoles());
    }

    private function findUserByEmail($request)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $request->get('email')]);

        if (!$user) {
            throw $this->createNotFoundException(
                'No User found for email '. $request->get('email')
            );
        }

        return $user;
    }
}
