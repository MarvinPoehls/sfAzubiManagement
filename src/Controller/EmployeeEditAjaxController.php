<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeEditAjaxController extends AbstractController
{
    public function __construct(private readonly EmployeeRepository $repository,private readonly EntityManagerInterface $em){}

    #[Route(path: '/employee/edit/ajax', name: 'employeeEditAjax')]
    public function index(Request $request): JsonResponse
    {
        $action = $request->request->get('action');

        if ($action) {
            if (method_exists($this, $action)) {
                return $this->{$action}($request);
            } else {
                return new JsonResponse(['error' => 'Invalid action'], 400);
            }
        } else {
            return new JsonResponse(['error' => 'No action specified'], 400);
        }
    }

    protected function deleteEmployees(Request $request): JsonResponse
    {
        $deleteIds = $request->get('ids');

        if (!$deleteIds || count($deleteIds) < 1)
            return new JsonResponse(['error' => 'No ids provided'], 400);

        foreach ($deleteIds as $deleteId)
            $this->em->remove($this->repository->find($deleteId));
        $this->em->flush();

        return new JsonResponse(['success' => true], 200);
    }
}