<?php

namespace App\Entity;

use App\Repository\PlatsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlatsRepository::class)
 */
class Plats
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
     * @ORM\OneToMany(targetEntity=Recette::class, mappedBy="plats_id")
     */
    private $recettes_id;

    public function __construct()
    {
        $this->recettes_id = new ArrayCollection();
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
     * @return Collection|Recette[]
     */
    public function getRecettesId(): Collection
    {
        return $this->recettes_id;
    }

    public function addRecettesId(Recette $recettesId): self
    {
        if (!$this->recettes_id->contains($recettesId)) {
            $this->recettes_id[] = $recettesId;
            $recettesId->setPlatsId($this);
        }

        return $this;
    }

    public function removeRecettesId(Recette $recettesId): self
    {
        if ($this->recettes_id->removeElement($recettesId)) {
            // set the owning side to null (unless already changed)
            if ($recettesId->getPlatsId() === $this) {
                $recettesId->setPlatsId(null);
            }
        }

        return $this;
    }
}
