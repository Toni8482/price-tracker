<?php

namespace App\Entity;

use App\Repository\StoresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StoresRepository::class)]
class Stores
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $base_url = null;

    #[ORM\Column(length: 255)]
    private ?string $logo = null;

    #[ORM\OneToMany(
        mappedBy: 'store',
        targetEntity: Perfumes::class
    )]
    private Collection $perfumes;

    public function __construct()
    {
        $this->perfumes = new ArrayCollection();
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

    public function getBaseUrl(): ?string
    {
        return $this->base_url;
    }

    public function setBaseUrl(string $base_url): static
    {
        $this->base_url = $base_url;
        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;
        return $this;
    }

    /** @return Collection<int, Perfumes> */
    public function getPerfumes(): Collection
    {
        return $this->perfumes;
    }
}
