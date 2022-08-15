<?php

namespace App\Controller\Api;

use App\Entity\Employee;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Api\Requests\Employee\EmployeeCreateRequest;
use App\Controller\Api\Requests\Employee\EmployeeUpdateRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\Api\Requests\Employee\EmployeeShowAndDeleteRequest;

class ApiEmployeeController extends AbstractController
{
    public function __construct(ManagerRegistry $entityManager)
    {
        $this->entityManager = $entityManager->getManager();
    }

    #[Route('/api/employee/create', name: 'api_create_employee', methods: ['POST'])]
    public function apiCreateEmployee(EmployeeCreateRequest $request): JsonResponse
    {
        $employee = new Employee();
        $request = $request->getRequest();

        $this->createEntityFromRequest($employee, $request);

        $this->entityManager->persist($employee);
        $this->entityManager->flush();

        return $this->json([
            'status' => 200,
            'success' => "Employee created successfully",
        ]);
    }

    #[Route('/api/employee/update', name: 'api_update_employee', methods: ['POST'])]
    public function apiUpdateEmployee(EmployeeUpdateRequest $request): JsonResponse
    {
        $request = $request->getRequest();
        $employee = $this->findEmployeeByEmail($request);

        $this->createEntityFromRequest($employee, $request);
        $this->entityManager->flush();

        return $this->json([
            'status' => 200,
            'success' => "Employee updated successfully",
        ]);
    }

    #[Route('/api/employee/show', name: 'api_show_employee', methods: ['POST'])]
    public function apiShowEmployee(EmployeeShowAndDeleteRequest $request): JsonResponse
    {
        $request = $request->getRequest();
        $employee = $this->findEmployeeByEmail($request);

        return $this->json([
            'status' => 200,
            'data' => $employee,
        ]);
    }

    #[Route('/api/employee/delete', name: 'api_delete_employee', methods: ['POST'])]
    public function apiDeleteEmployee(EmployeeShowAndDeleteRequest $request): JsonResponse
    {
        $request = $request->getRequest();
        $employee = $this->findEmployeeByEmail($request);

        $this->entityManager->remove($employee);
        $this->entityManager->flush();

        return $this->json([
            'status' => 200,
            'success' => "Employee deleted successfully",
        ]);
    }

    private function createEntityFromRequest(Employee $employee, $request): void
    {
        $employee->setFirstName($request->get('first_name'));
        $employee->setLastName($request->get('last_name'));
        $employee->setEmail($request->get('email'));
        $employee->setPhone($request->get('phone'));
    }

    private function findEmployeeByEmail($request)
    {
        $employee = $this->entityManager->getRepository(Employee::class)->findOneBy(['email' => $request->get('email')]);

        if (!$employee) {
            throw $this->createNotFoundException(
                'No Employee found for email '. $request->get('email')
            );
        }

        return $employee;
    }
}
