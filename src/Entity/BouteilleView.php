<?php

namespace App\Entity;

use App\Repository\BouteilleViewRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BouteilleViewRepository::class)]
class BouteilleView
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bouteilleViews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'bouteilleViews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bouteille $bouteille = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getBouteille(): ?Bouteille
    {
        return $this->bouteille;
    }

    public function setBouteille(?Bouteille $bouteille): static
    {
        $this->bouteille = $bouteille;

        return $this;
    }
}
