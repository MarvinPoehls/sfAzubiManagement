<?php

namespace App\Controller;

use App\Form\Type\SearchbarType;
use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AzubiListController extends AbstractController
{
    public function __construct(private readonly EmployeeRepository $repository){}

    #[Route(path: '/', name: 'list')]
    public function index(Request $request)
    {
        $limit = $request->get('limit') ?? 10;
        $currentPage = $request->get('page') ?? 1;
        $offset = ($currentPage * $limit) - $limit;
        $employeeCount = $this->repository->getEmployeeCount($request->get('search'));
        $pages = (int) ceil($employeeCount/$limit);

        $options = [];
        $search = $request->get('search');
        if ($search) {
            $options['search'] = $search;
        }

        $searchForm = $this->createForm(SearchbarType::class, null, $options);

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            return $this->redirectToRoute('list', ['search' => $searchForm->getData()->getSearch()]);
        }

        $employees = $this->repository->findBySearchQuery($search, null, $limit, $offset);

        return $this->render('list.html.twig', [
            'employees' => $employees,
            'currentPage' => $currentPage,
            'pages' => $pages,
            'searchbarForm' => $searchForm,
        ]);
    }
}