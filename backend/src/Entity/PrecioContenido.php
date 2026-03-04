<?php

namespace App\Entity;

use App\Repository\PrecioContenidoRepository;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Perfumes;
#[ORM\Entity(repositoryClass: PrecioContenidoRepository::class)]
class PrecioContenido
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   

    #[ORM\Column]
    private ?float $precio = null;

    #[ORM\Column(length: 255)]
    private ?string $contenido = null;


    #[ORM\ManyToOne(targetEntity: Perfumes::class)]
    #[ORM\JoinColumn(
        name: 'perfume_id',
        referencedColumnName: 'id',
        nullable: false
    )]
    private ?Perfumes $perfume = null;

    #[ORM\Column(length: 255)]
    private ?string $image_url = null;



    public function getId(): ?int
    {
        return $this->id;
    }

   

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): static
    {
        $this->precio = $precio;

        return $this;
    }

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): static
    {
        $this->contenido = $contenido;

        return $this;
    }


      public function getPerfumes(): ?Perfumes
    {
        return $this->perfume;
    }

    public function setPerfumes(Perfumes $perfume): static
    {
        $this->perfume = $perfume;
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
   
}
