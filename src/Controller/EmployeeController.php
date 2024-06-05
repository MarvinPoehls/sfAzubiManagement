<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeController extends AbstractController
{
    public function __construct(private readonly EmployeeRepository $repository)
    {
    }

    #[Route(path: '/employee', name: 'employee')]
    public function new(Request $request): Response
    {
        $id = $request->get('id');
        $employee = $this->repository->getEmployee($id);

        if (!$employee->getId()) {
            return $this->redirectToRoute('list');
        }

        return $this->render('employee.html.twig', [
            'entity' => $employee,
            'atFatchipSince' => $this->formatAtFatchipSince($employee->getAtFatchipSince()),
        ]);
    }

    private function formatAtFatchipSince(\DateTime $atFatchipSince): string
    {
        $currentDate = new \DateTime();

        $interval = $currentDate->diff($atFatchipSince);

        $formattedString = '';

        if ($interval->d > 1) {
            $formattedString .= $interval->d.' Tagen';
        } elseif (1 == $interval->d) {
            $formattedString .= 'einem Tag';
        }

        if ($interval->m > 1) {
            $formattedString .= ($formattedString ? ', ' : '').$interval->m.' Monaten';
        } elseif (1 == $interval->m) {
            $formattedString .= ($formattedString ? ', ' : '').'einem Monat';
        }

        if ($interval->y > 1) {
            $formattedString .= ($formattedString ? ', ' : '').$interval->y.' Jahren';
        } elseif (1 == $interval->y) {
            $formattedString .= ($formattedString ? ', ' : '').'einem Jahr';
        }

        if (empty($formattedString)) {
            return 'heute';
        }

        return $this->replaceLastComma($formattedString);
    }

    public function replaceLastComma($string): string
    {
        $lastCommaPos = strrpos($string, ',');

        if (false === $lastCommaPos) {
            return $string;
        }

        return substr_replace($string, ' und', $lastCommaPos, 1);
    }
}
