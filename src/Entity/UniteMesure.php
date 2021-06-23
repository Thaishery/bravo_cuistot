<?php

namespace App\Entity;

use App\Repository\UniteMesureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UniteMesureRepository::class)
 */
class UniteMesure
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=IngredientsRecette::class, mappedBy="unitemesure_id")
     */
    private $ingredientsRecettes;

    public function __construct()
    {
        $this->ingredientsRecettes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|IngredientsRecette[]
     */
    public function getIngredientsRecettes(): Collection
    {
        return $this->ingredientsRecettes;
    }

    public function addIngredientsRecette(IngredientsRecette $ingredientsRecette): self
    {
        if (!$this->ingredientsRecettes->contains($ingredientsRecette)) {
            $this->ingredientsRecettes[] = $ingredientsRecette;
            $ingredientsRecette->setUnitemesureId($this);
        }

        return $this;
    }

    public function removeIngredientsRecette(IngredientsRecette $ingredientsRecette): self
    {
        if ($this->ingredientsRecettes->removeElement($ingredientsRecette)) {
            // set the owning side to null (unless already changed)
            if ($ingredientsRecette->getUnitemesureId() === $this) {
                $ingredientsRecette->setUnitemesureId(null);
            }
        }

        return $this;
    }
}
