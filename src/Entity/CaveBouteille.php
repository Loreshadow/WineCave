<?php

namespace App\Entity;

use App\Repository\CaveBouteilleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaveBouteilleRepository::class)]
class CaveBouteille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'caveBouteilles')]
    private ?Cave $cave = null;

    #[ORM\ManyToOne(inversedBy: 'caveBouteilles')]
    private ?Bouteille $bouteille = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCave(): ?Cave
    {
        return $this->cave;
    }

    public function setCave(?Cave $cave): static
    {
        $this->cave = $cave;

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
