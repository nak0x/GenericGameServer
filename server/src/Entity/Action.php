<?php

namespace App\Entity;

use App\Enum\ActionMethods;
use App\Repository\ActionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActionRepository::class)]
class Action
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: ActionMethods::class)]
    private ?ActionMethods $method = null;

    #[ORM\ManyToOne(inversedBy: 'actions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Endpoint $endpoint = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMethod(): ?ActionMethods
    {
        return $this->method;
    }

    public function setMethod(ActionMethods $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function getEndpoint(): ?Endpoint
    {
        return $this->endpoint;
    }

    public function setEndpoint(?Endpoint $endpoint): static
    {
        $this->endpoint = $endpoint;

        return $this;
    }
}
