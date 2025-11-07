<?php

namespace App\Entity;

use App\Repository\RessourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: RessourceRepository::class)]
class Ressource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128)]
    private ?string $name = null;

    /**
     * @var Collection<int, Data>
     */
    #[ORM\OneToMany(targetEntity: Data::class, mappedBy: 'ressource', orphanRemoval: true)]
    private Collection $data;

    /**
     * @var Collection<int, DataType>
     */
    #[ORM\ManyToMany(targetEntity: DataType::class, mappedBy: 'ressources')]
    private Collection $dataTypes;

    #[ORM\ManyToOne(inversedBy: 'ressources')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    /**
     * @var Collection<int, Endpoint>
     */
    #[ORM\ManyToMany(targetEntity: Endpoint::class, inversedBy: 'ressources')]
    private Collection $endpoints;

    public function __construct()
    {
        $this->data = new ArrayCollection();
        $this->dataTypes = new ArrayCollection();
        $this->endpoints = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Data>
     */
    public function getData(): Collection
    {
        return $this->data;
    }

    public function addData(Data $data): static
    {
        if (!$this->data->contains($data)) {
            $this->data->add($data);
            $data->setRessource($this);
        }

        return $this;
    }

    public function removeData(Data $data): static
    {
        if ($this->data->removeElement($data)) {
            // set the owning side to null (unless already changed)
            if ($data->getRessource() === $this) {
                $data->setRessource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DataType>
     */
    public function getDataTypes(): Collection
    {
        return $this->dataTypes;
    }

    public function addDataType(DataType $dataType): static
    {
        if (!$this->dataTypes->contains($dataType)) {
            $this->dataTypes->add($dataType);
            $dataType->addRessource($this);
        }

        return $this;
    }

    public function removeDataType(DataType $dataType): static
    {
        if ($this->dataTypes->removeElement($dataType)) {
            $dataType->removeRessource($this);
        }

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    /**
     * @return Collection<int, Endpoint>
     */
    public function getEndpoints(): Collection
    {
        return $this->endpoints;
    }

    public function addEndpoint(Endpoint $endpoint): static
    {
        if (!$this->endpoints->contains($endpoint)) {
            $this->endpoints->add($endpoint);
        }

        return $this;
    }

    public function removeEndpoint(Endpoint $endpoint): static
    {
        $this->endpoints->removeElement($endpoint);

        return $this;
    }
}
