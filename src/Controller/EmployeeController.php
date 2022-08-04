<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Employee;
use App\Entity\Service;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    public function __construct(ManagerRegistry $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/barbers', name: 'employee')]
    public function index(): Response
    {
        $employeeRepository = $this->entityManager->getManager()->getRepository(Employee::class);
        $serviceRepository = $this->entityManager->getManager()->getRepository(Service::class);
        $employees = $employeeRepository->findAll();
        $services = $serviceRepository->findAll();

        return $this->render('employee/index.html.twig', [
            'services' => $services,
            'employees' => $employees,
        ]);
    }

    #[Route("/barbers/search-results/{page}", name: "barber_search_results", defaults: ["page" => "1"], methods: ["GET"] )]
    public function searchResults($page, Request $request)
    {
        $employees = null;
        $query = null;

        if($query = $request->get('query'))
        {
            $employees = $this->entityManager
                ->getManager()
                ->getRepository(Employee::class)->findByRequest($query, $page, $request->get('sortby'));
        }

        return $this->render('employee/index.html.twig',[
            'employees' => $employees,
            'query' => $query,
        ]);
    }

    #[Route('/barbers/info', name: 'employee_info')]
    public function infoEmployee(Request $request) {
        $comments = null;
        $employeeId = $request->get('id');

        $employeeRepository = $this->entityManager->getManager()->getRepository(Employee::class);
        $employee = $employeeRepository->findOneBy(['id' => $employeeId]);

        if (!$employee->getComments()->isEmpty()) {
            $comments = $employee->getComments();
        }

        return $this->render('employee/card-employee.html.twig', [
            'employee' => $employee,
            'comments' => $comments,
        ]);
    }

    #[Route('/barbers/info/save', name: 'save_comment', methods: ['POST'])]
    public function saveComment(Request $request) {
        $employeeId = $request->get('employee_id');
        $userId = $this->getUser()->getId();
        $content = $request->get('content');

        $comment = new Comment();

        $comment->setUserComment($this->entityManager->getManager()->find(User::class, $userId));
        $comment->setEmployee($this->entityManager->getManager()->find(Employee::class, $employeeId));
        $comment->setContent($content);

        $this->entityManager->getManager()->persist($comment);
        $this->entityManager->getManager()->flush();

        return $this->redirectToRoute('employee_info', ['id' => $employeeId]);
    }
}
