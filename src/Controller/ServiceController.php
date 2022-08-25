<?php

namespace App\Controller;

use App\Entity\Service;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServiceController extends AbstractController
{
    private ManagerRegistry $entityManager;

    public function __construct(ManagerRegistry $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/service', name: 'service')]
    public function index(): Response
    {
        $serviceRepository = $this->entityManager->getManager()->getRepository(Service::class);
        $services = $serviceRepository->findAll();

        return $this->render('service/index.html.twig', [
            'services' => $services,
        ]);
    }

    #[Route('/service/more', name: 'service_more')]
    public function infoService(Request $request): Response
    {
        $serviceId = $request->get('id');

        $serviceRepository = $this->entityManager->getManager()->getRepository(Service::class);
        $service = $serviceRepository->findOneBy(['id' => $serviceId]);

        return $this->render('service/card-service.html.twig', [
            'service' => $service,
        ]);
    }
}
