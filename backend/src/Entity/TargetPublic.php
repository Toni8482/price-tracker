<?php

namespace App\Entity;

use App\Repository\TargetPublicRepository;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TargetPublicRepository::class)]
class TargetPublic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;


    #[ORM\OneToMany(
        mappedBy: 'target_public',
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

    /** @return Collection<int, Perfumes> */
    public function getPerfumes(): Collection
    {
        return $this->perfumes;
    }
}
