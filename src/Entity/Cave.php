<?php

namespace App\Entity;

use App\Repository\CaveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaveRepository::class)]
class Cave
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'cave')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', onDelete: 'CASCADE', nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'integer')]
    private ?int $visibility = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, Bouteille>
     */
    #[ORM\OneToMany(targetEntity: Bouteille::class, mappedBy: 'cave')]
    private Collection $bottles;

    /**
     * @var Collection<int, CaveBouteille>
     */
    #[ORM\OneToMany(targetEntity: CaveBouteille::class, mappedBy: 'cave')]
    private Collection $caveBouteilles;

    public function __construct()
    {
        $this->bottles = new ArrayCollection();
        $this->caveBouteilles = new ArrayCollection();
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getVisibility(): ?int
    {
        return $this->visibility;
    }
public function setVisibility(int $visibility): static
{
    $this->visibility = $visibility;
    return $this;
}

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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
            $bottle->setCave($this);
        }

        return $this;
    }

    public function removeBottle(Bouteille $bottle): static
    {
        if ($this->bottles->removeElement($bottle)) {
            // set the owning side to null (unless already changed)
            if ($bottle->getCave() === $this) {
                $bottle->setCave(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CaveBouteille>
     */
    public function getCaveBouteilles(): Collection
    {
        return $this->caveBouteilles;
    }

    public function addCaveBouteille(CaveBouteille $caveBouteille): static
    {
        if (!$this->caveBouteilles->contains($caveBouteille)) {
            $this->caveBouteilles->add($caveBouteille);
            $caveBouteille->setCave($this);
        }

        return $this;
    }

    public function removeCaveBouteille(CaveBouteille $caveBouteille): static
    {
        if ($this->caveBouteilles->removeElement($caveBouteille)) {
            // set the owning side to null (unless already changed)
            if ($caveBouteille->getCave() === $this) {
                $caveBouteille->setCave(null);
            }
        }

        return $this;
    }
}
