<?php

namespace App\Controller;

use App\Controller\Admin\EmployeeCrudController;
use App\Entity\Comment;
use App\Entity\Employee;
use App\Entity\Service;
use App\Entity\User;
use App\Form\AdminNewEmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    public function __construct(AdminUrlGenerator $crudUrlGenerator, ManagerRegistry $entityManager, EmployeeRepository $employeeRepository)
    {
        $this->entityManager = $entityManager;
        $this->employeeRepository = $employeeRepository;
        $this->crudUrlGenerator = $crudUrlGenerator;
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

    #[Route("/barbers/search-results/{page}", name: "barber_search_results", defaults: ["page" => "1"], methods: ["GET"])]
    public function searchResults($page, Request $request)
    {
        $employees = null;
        $query = null;

        if ($query = $request->get('query')) {
            $employees = $this->entityManager
                ->getManager()
                ->getRepository(Employee::class)->findByRequest($query, $page, $request->get('sortby'));
        }

        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
            'query' => $query,
        ]);
    }

    #[Route('/barbers/info', name: 'employee_info')]
    public function infoEmployee(Request $request)
    {
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
    public function saveComment(Request $request)
    {
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

    #[Route('/admin/employee/new', name: 'admin_new_employee')]
    public function adminNewEmployee(Request $request)
    {
        $employeeId = $request->get('uuid');
        $employee = new Employee();

        if ($employeeId !== null) {
            $repository = $this->entityManager->getManager()->getRepository(Employee::class);
            $employee = $repository->findOneBy(['id' => $employeeId]);
        }

        $form = $this->createForm(AdminNewEmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->employeeRepository->add($employee, true);

            $url = $this->crudUrlGenerator
                ->setController(EmployeeCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl();

            return $this->redirect($url);
        }

        return $this->renderForm('admin/new_employee.html.twig', [
            'form' => $form,
            'workDay' => $employee->getWorkDays(),
        ]);
    }
}
