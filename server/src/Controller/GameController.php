<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game')]
    public function index(GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findAll();

        return $this->render('game/index.html.twig', [
            'games' => $games,
        ]);
    }

    #[Route('/dashboard/game/create', name: 'app_game_create', methods: ['GET','POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($game);
            $em->flush();
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('dashboard/game/game_form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/dashboard/game/edit/{id}', name: 'app_game_edit', methods: ['GET','POST'])]
    public function edit(int $id, Request $request, EntityManagerInterface $em, GameRepository $gameRepository): Response
    {
        $game = $gameRepository->find($id);
        $form = $this->createForm(GameType::class, $game);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($game);
            $em->flush();
            return $this->redirectToRoute('app_game_edit', ['id' => $id]);
        }

        return $this->render('/dashboard/game/game_form.html.twig', [
            'form' => $form,
            'game' => $game,
        ]);
    }

    #[Route('/dashboard/game/delete/{id}', name: 'app_game_delete', methods: ['POST'])]
    public function delete(int $id, Request $request, GameRepository $gameRepository, EntityManagerInterface $em): RedirectResponse
    {
        $game = $gameRepository->find($id);

        if (!$game) {
            throw $this->createNotFoundException('Game not found');
        }

        // (Optional) CSRF protection
        if ($this->isCsrfTokenValid('delete_game_' . $game->getId(), $request->request->get('_token'))) {
            $em->remove($game);
            $em->flush();

            $this->addFlash('success', sprintf('Game "%s" deleted successfully.', $game->getName()));
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_dashboard');
    }
}
