<?php

namespace App\Entity;

use App\Repository\ColorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColorRepository::class)]
class Color
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Bouteille>
     */
    #[ORM\OneToMany(targetEntity: Bouteille::class, mappedBy: 'color')]
    private Collection $bottles;

    public function __construct()
    {
        $this->bottles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Bouteille>
     */
    public function getBottles(): Collection
    {
        return $this->bottles;
    }

    public function addBottle(Bouteille $bottle): static
    {
        if (!$this->bottles->contains($bottle)) {
            $this->bottles->add($bottle);
            $bottle->setColor($this);
        }

        return $this;
    }

    public function removeBottle(Bouteille $bottle): static
    {
        if ($this->bottles->removeElement($bottle)) {
            // set the owning side to null (unless already changed)
            if ($bottle->getColor() === $this) {
                $bottle->setColor(null);
            }
        }

        return $this;
    }
}
