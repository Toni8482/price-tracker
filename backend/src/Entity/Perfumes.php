<?php

namespace App\Entity;

use App\Entity\Stores;
use App\Entity\TargetPublic;
use App\Entity\PrecioContenido;

use App\Repository\PerfumesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: PerfumesRepository::class)]
class Perfumes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

   
    #[ORM\Column(length: 255)]
    private ?string $perfume_url = null;

    #[ORM\Column(length: 100)]
    private ?string $category = null;

   

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

    #[ORM\Column(length: 100)]
    private ?string $brand = null;

   

    #[ORM\Column(length: 100)]
    private ?string $concentracion = null;

    #[ORM\ManyToOne(targetEntity: TargetPublic::class)]
    #[ORM\JoinColumn(
        name: 'target_public_id',
        referencedColumnName: 'id',
        nullable: false
    )]
    private ?TargetPublic $target_public = null;


    #[ORM\OneToMany(
        mappedBy: 'precioContenido',
        targetEntity: PrecioContenido::class
    )]
    private Collection $preciosContenidos;

    public function __construct()
    {
        $this->preciosContenidos = new ArrayCollection();
    }

    /** @return Collection<int, PrecioContenido> */
    public function getPrecioContenido(): Collection
    {
        return $this->preciosContenidos;
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

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

   

    public function getConcentracion(): ?string
    {
        return $this->concentracion;
    }

    public function setConcentracion(string $concentracion): static
    {
        $this->concentracion = $concentracion;

        return $this;
    }

    public function getTargetPublic(): ?TargetPublic
    {
        return $this->target_public;
    }

    public function setTargetPublic(TargetPublic $targetPublic): static
    {
        $this->target_public =  $targetPublic;
        return $this;
    }
}
