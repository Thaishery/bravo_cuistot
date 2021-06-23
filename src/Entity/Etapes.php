<?php

namespace App\Entity;

use App\Repository\EtapesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtapesRepository::class)
 */
class Etapes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $is_number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Recette::class, inversedBy="etapes_id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recette_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsNumber(): ?int
    {
        return $this->is_number;
    }

    public function setIsNumber(int $is_number): self
    {
        $this->is_number = $is_number;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRecetteId(): ?Recette
    {
        return $this->recette_id;
    }

    public function setRecetteId(?Recette $recette_id): self
    {
        $this->recette_id = $recette_id;

        return $this;
    }
}
