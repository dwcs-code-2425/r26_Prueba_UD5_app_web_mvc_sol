<?php

namespace App\Entity;

use App\Repository\NotaRepository;
use DateTime;


use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: NotaRepository::class)]

class Nota
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $titulo = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;


    #[ORM\Column(type: 'datetime', nullable:true)]
    private ?DateTime $fechaModificacion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFechaModificacion(): ?DateTime
    {
        return $this->fechaModificacion;
    }

    public function setFechaModificacion(?DateTime $fechaModificacion): static
    {
        $this->fechaModificacion = $fechaModificacion;

        return $this;
    }

  
}