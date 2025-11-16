<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\User;
use App\Enum\Roles;
use App\Form\GameUserType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameUserController extends AbstractController
{
    #[Route('/dashboard/games/{slug}/users/new', name: 'app_game_user_new')]
    public function newForGame(
        string $slug,
        Request $request,
        EntityManagerInterface $em,
        GameRepository $gameRepository,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $game = $gameRepository->findOneBy(['slug' => $slug]);
        $user = new User();
        // associate user to this game
        $user->addGame($game);

        $form = $this->createForm(GameUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Roles[] $selectedRoles */
            $selectedRoles = $form->get('rolesEnum')->getData() ?? [];

            // Convert enums to role strings
            $roleStrings = array_map(static fn (Roles $role) => $role->value, $selectedRoles);
            $user->setRoles($roleStrings);

            // Hash password
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'User created for this game.');

            return $this->redirectToRoute('app_game_show', [
                'slug' => $game->getSlug(),
            ]);
        }

        return $this->render('dashboard/game/add_user.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }
}
