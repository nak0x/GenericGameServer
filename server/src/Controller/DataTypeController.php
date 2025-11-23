<?php

namespace App\Controller;

use App\Entity\DataType;
use App\Form\DataTypeType;
use App\Repository\DataTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DataTypeController extends AbstractController
{
    #[Route('/data/type', name: 'app_data_type')]
    public function index(): Response
    {
        return $this->render('data_type/index.html.twig', [
            'controller_name' => 'DataTypeController',
        ]);
    }

    #[Route('/dashboard/datatype', name: 'app_dashboard_datatype', methods: ['GET'])]
    public function list(DataTypeRepository $datatypeRepository): Response
    {
        return $this->render('dashboard/datatype/list.html.twig', [
            'datatypes' => $datatypeRepository->findAll(),
        ]);
    }

    #[Route('/dashboard/datatype/{id}', name: 'app_dashboard_datatype_show', methods: ['GET'])]
    public function show(DataType $datatype): Response
    {
        return $this->render('dashboard/datatype/show.html.twig', [
            'datatype' => $datatype,
        ]);
    }

    #[Route('/dashboard/datatype/{id}/edit', name: 'app_dashboard_datatype_form', methods: ['GET'])]
    public function edit(
        int $id,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $datatype = $em->getRepository(DataType::class)->find($id);

        if (!$datatype) {
            throw $this->createNotFoundException('Datatype not found.');
        }

        $form = $this->createForm(DataTypeType::class, $datatype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_game_show', [
                'slug' => $datatype->getGame()->getSlug(),
            ]);
        }

        return $this->render('dashboard/datatype/form.html.twig', [
            'form' => $form->createView(),
            'datatype' => $datatype,
        ]);
    }
    #[Route('/datatype/{id}', name: 'app_datatype_delete', methods: ['POST'])]
    public function delete(
        int $id,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $datatype = $em->getRepository(DataType::class)->find($id);

        if (!$datatype) {
            throw $this->createNotFoundException('Datatype not found.');
        }

        if (!$this->isCsrfTokenValid('delete_datatype_'.$datatype->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        $em->remove($datatype);
        $em->flush();

        return $this->redirectToRoute('app_dashboard_datatype');
    }
}
