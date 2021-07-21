<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecetteRepository::class)
 */
class Recette
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
     * @ORM\OneToMany(targetEntity=IngredientsRecette::class, mappedBy="recette_id")
     */
    private $ingredientsRecette_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Etapes::class, mappedBy="recette_id")
     */
    private $etapes_id;

    /**
     * @ORM\OneToMany(targetEntity=Commentaires::class, mappedBy="recette_id")
     */
    private $commentaires_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $temps_preparation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $temps_cuisson;

    /**
     * @ORM\OneToMany(targetEntity=Notes::class, mappedBy="recette_id")
     */
    private $notes_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nb_personnes;

    /**
     * @ORM\Column(type="integer")
     */
    private $difficulte;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recettes_id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author_id;

    /**
     * @ORM\ManyToOne(targetEntity=Cuisson::class, inversedBy="recettes_id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cuisson_id;

    /**
     * @ORM\ManyToOne(targetEntity=Alimentation::class, inversedBy="recettes_id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $alimentation_id;

    /**
     * @ORM\ManyToOne(targetEntity=Plats::class, inversedBy="recettes_id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plats_id;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="recettes_fav_id")
     */
    private $users_fav_id;

    public function __construct()
    {
        $this->ingredientsRecette_id = new ArrayCollection();
        $this->etapes_id = new ArrayCollection();
        $this->commentaires_id = new ArrayCollection();
        $this->notes_id = new ArrayCollection();
        $this->users_fav_id = new ArrayCollection();
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
    public function getIngredientsRecetteId(): Collection
    {
        return $this->ingredientsRecette_id;
    }

    public function addIngredientsRecetteId(IngredientsRecette $ingredientsRecetteId): self
    {
        if (!$this->ingredientsRecette_id->contains($ingredientsRecetteId)) {
            $this->ingredientsRecette_id[] = $ingredientsRecetteId;
            $ingredientsRecetteId->setRecetteId($this);
        }

        return $this;
    }

    public function removeIngredientsRecetteId(IngredientsRecette $ingredientsRecetteId): self
    {
        if ($this->ingredientsRecette_id->removeElement($ingredientsRecetteId)) {
            // set the owning side to null (unless already changed)
            if ($ingredientsRecetteId->getRecetteId() === $this) {
                $ingredientsRecetteId->setRecetteId(null);
            }
        }

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

    /**
     * @return Collection|etapes[]
     */
    public function getEtapesId(): Collection
    {
        return $this->etapes_id;
    }

    public function addEtapesId(etapes $etapesId): self
    {
        if (!$this->etapes_id->contains($etapesId)) {
            $this->etapes_id[] = $etapesId;
            $etapesId->setRecetteId($this);
        }

        return $this;
    }

    public function removeEtapesId(etapes $etapesId): self
    {
        if ($this->etapes_id->removeElement($etapesId)) {
            // set the owning side to null (unless already changed)
            if ($etapesId->getRecetteId() === $this) {
                $etapesId->setRecetteId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaires[]
     */
    public function getCommentairesId(): Collection
    {
        return $this->commentaires_id;
    }

    public function addCommentairesId(Commentaires $commentairesId): self
    {
        if (!$this->commentaires_id->contains($commentairesId)) {
            $this->commentaires_id[] = $commentairesId;
            $commentairesId->setRecetteId($this);
        }

        return $this;
    }

    public function removeCommentairesId(Commentaires $commentairesId): self
    {
        if ($this->commentaires_id->removeElement($commentairesId)) {
            // set the owning side to null (unless already changed)
            if ($commentairesId->getRecetteId() === $this) {
                $commentairesId->setRecetteId(null);
            }
        }

        return $this;
    }

    public function getTempsPreparation(): ?int
    {
        return $this->temps_preparation;
    }

    public function setTempsPreparation(?int $temps_preparation): self
    {
        $this->temps_preparation = $temps_preparation;

        return $this;
    }

    public function getTempsCuisson(): ?int
    {
        return $this->temps_cuisson;
    }

    public function setTempsCuisson(?int $temps_cuisson): self
    {
        $this->temps_cuisson = $temps_cuisson;

        return $this;
    }

    /**
     * @return Collection|Notes[]
     */
    public function getNotesId(): Collection
    {
        return $this->notes_id;
    }

    public function addNotesId(Notes $notesId): self
    {
        if (!$this->notes_id->contains($notesId)) {
            $this->notes_id[] = $notesId;
            $notesId->setRecetteId($this);
        }

        return $this;
    }

    public function removeNotesId(Notes $notesId): self
    {
        if ($this->notes_id->removeElement($notesId)) {
            // set the owning side to null (unless already changed)
            if ($notesId->getRecetteId() === $this) {
                $notesId->setRecetteId(null);
            }
        }

        return $this;
    }

    public function getNbPersonnes(): ?int
    {
        return $this->nb_personnes;
    }

    public function setNbPersonnes(?int $nb_personnes): self
    {
        $this->nb_personnes = $nb_personnes;

        return $this;
    }

    public function getDifficulte(): ?int
    {
        return $this->difficulte;
    }

    public function setDifficulte(int $difficulte): self
    {
        $this->difficulte = $difficulte;

        return $this;
    }

    public function getAuthorId(): ?user
    {
        return $this->author_id;
    }

    public function setAuthorId(?user $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }

    public function getCuissonId(): ?cuisson
    {
        return $this->cuisson_id;
    }

    public function setCuissonId(?cuisson $cuisson_id): self
    {
        $this->cuisson_id = $cuisson_id;

        return $this;
    }

    public function getAlimentationId(): ?Alimentation
    {
        return $this->alimentation_id;
    }

    public function setAlimentationId(?Alimentation $alimentation_id): self
    {
        $this->alimentation_id = $alimentation_id;

        return $this;
    }

    public function getPlatsId(): ?Plats
    {
        return $this->plats_id;
    }

    public function setPlatsId(?Plats $plats_id): self
    {
        $this->plats_id = $plats_id;

        return $this;
    }

    /**
     * @return Collection|user[]
     */
    public function getUsersFavId(): Collection
    {
        return $this->users_fav_id;
    }

    public function addUsersFavId(user $usersFavId): self
    {
        if (!$this->users_fav_id->contains($usersFavId)) {
            $this->users_fav_id[] = $usersFavId;
        }

        return $this;
    }

    public function removeUsersFavId(user $usersFavId): self
    {
        $this->users_fav_id->removeElement($usersFavId);

        return $this;
    }

     public function __toString() {

         return "$this->id";
     }

 }
