<?php

namespace App\Entity;

use App\Repository\IngredientsRecetteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IngredientsRecetteRepository::class)
 */
class IngredientsRecette
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
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity=Ingredients::class, inversedBy="ingredientsRecettes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ingredients_id;

    /**
     * @ORM\ManyToOne(targetEntity=UniteMesure::class, inversedBy="ingredientsRecettes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $unitemesure_id;

    /**
     * @ORM\ManyToOne(targetEntity=Recette::class, inversedBy="ingredientsRecette_id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recette_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getIngredientsId(): ?Ingredients
    {
        return $this->ingredients_id;
    }

    public function setIngredientsId(?Ingredients $ingredients_id): self
    {
        $this->ingredients_id = $ingredients_id;

        return $this;
    }

    public function getUnitemesureId(): ?UniteMesure
    {
        return $this->unitemesure_id;
    }

    public function setUnitemesureId(?UniteMesure $unitemesure_id): self
    {
        $this->unitemesure_id = $unitemesure_id;

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
