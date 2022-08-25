<?php

namespace App\Controller\Api;

use App\Entity\Service;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\Api\Requests\Service\ServiceCreateRequest;
use App\Controller\Api\Requests\Service\ServiceUpdateRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\Api\Requests\Service\ServiceShowAndDeleteRequest;

class ApiServiceController extends AbstractController
{
    private ObjectManager $entityManager;

    public function __construct(ManagerRegistry $entityManager)
    {
        $this->entityManager = $entityManager->getManager();
    }

    #[Route('/api/service/create', name: 'api_create_service', methods: ['POST'])]
    public function apiCreateUser(ServiceCreateRequest $request): JsonResponse
    {
        $service = new Service();
        $request = $request->getRequest();

        $this->createEntityFromRequest($service, $request);

        $this->entityManager->persist($service);
        $this->entityManager->flush();

        return $this->json([
            'status' => 200,
            'success' => "Service created successfully",
        ]);
    }

    #[Route('/api/service/update', name: 'api_update_service', methods: ['POST'])]
    public function apiUpdateUser(ServiceUpdateRequest $request): JsonResponse
    {
        $request = $request->getRequest();
        $service = $this->findUserByEmail($request);

        $this->createEntityFromRequest($service, $request);
        $this->entityManager->flush();

        return $this->json([
            'status' => 200,
            'success' => "Service updated successfully",
        ]);
    }

    #[Route('/api/service/show', name: 'api_show_service', methods: ['POST'])]
    public function apiShowUser(ServiceShowAndDeleteRequest $request): JsonResponse
    {
        $request = $request->getRequest();
        $service = $this->findUserByEmail($request);

        return $this->json([
            'status' => 200,
            'data' => $service,
        ]);
    }

    #[Route('/api/service/delete', name: 'api_delete_service', methods: ['POST'])]
    public function apiDeleteUser(ServiceShowAndDeleteRequest $request): JsonResponse
    {
        $request = $request->getRequest();
        $service = $this->findUserByEmail($request);

        $this->entityManager->remove($service);
        $this->entityManager->flush();

        return $this->json([
            'status' => 200,
            'success' => "Service deleted successfully",
        ]);
    }

    private function createEntityFromRequest(Service $service, $request): void
    {
        $service->setTitle($request->get('title', $service->getTitle()));
        $service->setDescription($request->get('description', $service->getDescription()));
        $service->setPrice($request->get('price', $service->getPrice()));
    }

    private function findUserByEmail($request)
    {
        $service = $this->entityManager->getRepository(Service::class)->findOneBy(['title' => $request->get('title')]);

        if (!$service) {
            throw $this->createNotFoundException('No Service found');
        }

        return $service;
    }
}
