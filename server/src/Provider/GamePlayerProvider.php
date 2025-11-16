<?php

namespace App\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Game;
use App\Entity\User;
use App\Repository\GameRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GamePlayerProvider implements ProviderInterface
{
    public function __construct(
        private readonly GameRepository $gameRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        $slug = $uriVariables['slug'] ?? null;

        if (!$slug) {
            return [];
        }

        /** @var Game|null $game */
        $game = $this->gameRepository->findOneBy(['slug' => $slug]);

        if (!$game) {
            throw new NotFoundHttpException(sprintf('Game with slug "%s" not found.', $slug));
        }

        // Start from the game users and filter in PHP to keep it DB-agnostic
        $users = $game->getUsers()->filter(
            fn (User $user) => \in_array('ROLE_PLAYER', $user->getRoles(), true)
        );

        return $users;
    }
}
