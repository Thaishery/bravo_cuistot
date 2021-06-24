<?php

namespace App\Entity;

use App\Repository\CommentairesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentairesRepository::class)
 */
class Commentaires
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $edited_at;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity=Recette::class, inversedBy="commentaires_id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recette_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getEditedAt(): ?\DateTimeImmutable
    {
        return $this->edited_at;
    }

    public function setEditedAt(?\DateTimeImmutable $edited_at): self
    {
        $this->edited_at = $edited_at;

        return $this;
    }

    public function getUserId(): ?user
    {
        return $this->user_id;
    }

    public function setUserId(?user $user_id): self
    {
        $this->user_id = $user_id;

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
