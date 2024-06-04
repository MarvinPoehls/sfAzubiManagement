<?php

namespace App\Controller;

use App\Entity\EmployeeEntity;
use App\Form\Type\AddEmployeeType;
use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class EmployeeEditController extends AbstractController
{
    public function __construct(
        private readonly string $uploadDirectory,
        private readonly EmployeeRepository $repository,
        private readonly SluggerInterface $slugger,
    ){}

    #[Route(path: '/employee/edit', name: 'employeeEdit')]
    public function new(Request $request): RedirectResponse|Response
    {
        $id = $request->get('id');
        $employee = $this->repository->getEmployee($id);
        $isEdit = $employee->getId() !== null;

        $options = [];
        if ($isEdit) {
            $options = [
                'id' => $employee->getId(),
                'firstname' => $employee->getFirstname(),
                'lastname' => $employee->getLastname(),
                'birthday' => $employee->getBirthday(),
                'email' => $employee->getEmail(),
                'github' => $employee->getGithub(),
                'atFatchipSince' => $employee->getAtFatchipSince(),
            ];
        }

        $form = $this->createForm(AddEmployeeType::class, $employee, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EmployeeEntity $formData */
            $formData = $form->getData();

            $imageLink = $this->handleImageUpload($formData, $form->get('image')->getData(), $request->get('setDefaultImage'));

            $formData->removeDuplicateSkills();

            if ($isEdit) {
                $employee = $this->repository->find($id);
                $employee->assign($formData, (bool) $imageLink);
            } else {
                $this->repository->add($formData);
            }
            $this->repository->flush();

            if (!$isEdit) {
                $id = $formData->getId();
            }

            $this->addFlash('success', 'Erfolgreich gespeichert!');
            return $this->redirectToRoute('employeeEdit', ['id' => $id]);
        }

        return $this->render('employeeEdit.html.twig', [
            'addEmployeeForm' => $form,
            'entity' => $isEdit ? $employee : false,
            'id' => $isEdit ? $employee->getId() : null,
        ]);
    }

    protected function uploadFile(?UploadedFile $image): ?string
    {
        if (empty($image)) {
            return null;
        }

        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

        try {
            $image->move($this->uploadDirectory, $newFilename);
        } catch (FileException $e) {
            dd($e);
        }
        return $newFilename;
    }

    protected function handleImageUpload(EmployeeEntity $formData, ?UploadedFile $image, bool $setDefaultImage): ?string
    {
        $imageLink = $this->uploadFile($image);
        if ($imageLink) {
            $formData->setImage($imageLink);
        }
        if ($setDefaultImage) {
            $formData->setImage('defaultProfilePicture.png');
        }

        return $imageLink;
    }
}