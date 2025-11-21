<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Ressource;
use App\Form\RessourceType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class RessourceController extends AbstractController
{
    #[Route('/ressource', name: 'app_ressource')]
    public function index(): Response
    {
        return $this->render('ressource/index.html.twig', [
            'controller_name' => 'RessourceController',
        ]);
    }

    #[Route('/dashboard/games/{slug}/ressource/new', name: 'app_game_ressource_new')]
    public function newForGame(
        string $slug,
        Request $request,
        EntityManagerInterface $em,
        GameRepository $gameRepository
    ): Response
    {
        $game = $gameRepository->findOneBy(['slug' => $slug]);
        $ressource = new Ressource();
        $ressource->setGame($game);
        $form = $this->createForm(RessourceType::class, $ressource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ressource);
            $em->flush();

            // Redirect wherever makes sense (e.g. game show page)
            return $this->redirectToRoute('app_game_show', [
                'slug' => $game->getSlug(),
            ]);
        }

        return $this->render('dashboard/ressource/edit.html.twig', [
            'form' => $form->createView(),
            'game' => $game
        ]);
    }

    #[Route('/dashboard/ressource/{id}/edit', name: 'app_dashboard_ressource_edit')]
    public function edit(
        int $id,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $ressource = $em->getRepository(Ressource::class)->find($id);

        if (!$ressource) {
            throw $this->createNotFoundException('Ressource not found.');
        }

        $form = $this->createForm(RessourceType::class, $ressource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_game_show', [
                'slug' => $ressource->getGame()->getSlug(),
            ]);
        }

        return $this->render('dashboard/ressource/edit.html.twig', [
            'form' => $form->createView(),
            'ressource' => $ressource,
        ]);
    }

    #[Route('/ressource/{id}', name: 'app_ressource_delete', methods: ['POST'])]
    public function delete(
        int $id,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $ressource = $em->getRepository(Ressource::class)->find($id);

        if (!$ressource) {
            throw $this->createNotFoundException('Ressource not found.');
        }

        if (!$this->isCsrfTokenValid('delete_ressource_'.$ressource->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        $game = $ressource->getGame();

        $em->remove($ressource);
        $em->flush();

        return $this->redirectToRoute('app_game_show', [
            'slug' => $game->getSlug()
        ]);
    }
}
