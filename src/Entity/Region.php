<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'regions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?pays $country = null;

    /**
     * @var Collection<int, Appellation>
     */
    #[ORM\OneToMany(targetEntity: Appellation::class, mappedBy: 'region')]
    private Collection $appellations;

    /**
     * @var Collection<int, Bouteille>
     */
    #[ORM\OneToMany(targetEntity: Bouteille::class, mappedBy: 'region')]
    private Collection $bottles;

    public function __construct()
    {
        $this->appellations = new ArrayCollection();
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

    public function getCountry(): ?pays
    {
        return $this->country;
    }

    public function setCountry(?pays $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Appellation>
     */
    public function getAppellations(): Collection
    {
        return $this->appellations;
    }

    public function addAppellation(Appellation $appellation): static
    {
        if (!$this->appellations->contains($appellation)) {
            $this->appellations->add($appellation);
            $appellation->setRegion($this);
        }

        return $this;
    }

    public function removeAppellation(Appellation $appellation): static
    {
        if ($this->appellations->removeElement($appellation)) {
            // set the owning side to null (unless already changed)
            if ($appellation->getRegion() === $this) {
                $appellation->setRegion(null);
            }
        }

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
            $bottle->setRegion($this);
        }

        return $this;
    }

    public function removeBottle(Bouteille $bottle): static
    {
        if ($this->bottles->removeElement($bottle)) {
            // set the owning side to null (unless already changed)
            if ($bottle->getRegion() === $this) {
                $bottle->setRegion(null);
            }
        }

        return $this;
    }
}
