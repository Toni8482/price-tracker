<?php

namespace App\Entity;

use App\Entity\Stores;
use App\Repository\PerfumesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PerfumesRepository::class)]
class Perfumes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    private ?string $perfume_url = null;

    #[ORM\Column(length: 100)]
    private ?string $category = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column(length: 255)]
    private ?string $image_url = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;


    #[ORM\ManyToOne(targetEntity: Stores::class)]
    #[ORM\JoinColumn(
        name: 'store_id',
        referencedColumnName: 'id',
        nullable: false
    )]
    private ?Stores $store = null;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPerfumeUrl(): ?string
    {
        return $this->perfume_url;
    }

    public function setPerfumeUrl(string $perfume_url): static
    {
        $this->perfume_url = $perfume_url;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): static
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStore(): ?Stores
    {
        return $this->store;
    }

    public function setStore(Stores $store): static
    {
        $this->store = $store;
        return $this;
    }
}
