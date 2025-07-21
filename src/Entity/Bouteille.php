<?php

namespace App\Entity;

use App\Repository\BouteilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BouteilleRepository::class)]
class Bouteille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $ancien = null;

    #[ORM\ManyToOne(inversedBy: 'bottles')]
    private ?Color $color = null;

    #[ORM\ManyToOne(inversedBy: 'bottles')]
    private ?Region $region = null;

    #[ORM\ManyToOne(inversedBy: 'bottles')]
    private ?Appellation $appellation = null;

    #[ORM\ManyToOne(inversedBy: 'bottles')]
    private ?Producteur $producteur = null;

    #[ORM\Column(nullable: true)]
    private ?float $degres = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $purchasePrice = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Gout = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'bottles')]
    private ?Cave $cave = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, HistoriqueStock>
     */
    #[ORM\OneToMany(targetEntity: HistoriqueStock::class, mappedBy: 'bottles')]
    private Collection $historiqueStocks;

    /**
     * @var Collection<int, Favoris>
     */
    #[ORM\OneToMany(targetEntity: Favoris::class, mappedBy: 'bottle')]
    private Collection $favoris;

    /**
     * @var Collection<int, Wishlist>
     */
    #[ORM\OneToMany(targetEntity: Wishlist::class, mappedBy: 'bottle')]
    private Collection $wishlists;

    /**
     * @var Collection<int, Note>
     */
    #[ORM\OneToMany(targetEntity: Note::class, mappedBy: 'bottle')]
    private Collection $notes;

    public function __construct()
    {
        $this->historiqueStocks = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->wishlists = new ArrayCollection();
        $this->notes = new ArrayCollection();
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

    public function getAncien(): ?int
    {
        return $this->ancien;
    }

    public function setAncien(?int $ancien): static
    {
        $this->ancien = $ancien;

        return $this;
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getAppellation(): ?Appellation
    {
        return $this->appellation;
    }

    public function setAppellation(?Appellation $appellation): static
    {
        $this->appellation = $appellation;

        return $this;
    }

    public function getProducteur(): ?Producteur
    {
        return $this->producteur;
    }

    public function setProducteur(?Producteur $producteur): static
    {
        $this->producteur = $producteur;

        return $this;
    }

    public function getDegres(): ?float
    {
        return $this->degres;
    }

    public function setDegres(?float $degres): static
    {
        $this->degres = $degres;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPurchasePrice(): ?string
    {
        return $this->purchasePrice;
    }

    public function setPurchasePrice(?string $purchasePrice): static
    {
        $this->purchasePrice = $purchasePrice;

        return $this;
    }

    public function getGout(): ?string
    {
        return $this->Gout;
    }

    public function setGout(?string $Gout): static
    {
        $this->Gout = $Gout;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
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

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, HistoriqueStock>
     */
    public function getHistoriqueStocks(): Collection
    {
        return $this->historiqueStocks;
    }

    public function addHistoriqueStock(HistoriqueStock $historiqueStock): static
    {
        if (!$this->historiqueStocks->contains($historiqueStock)) {
            $this->historiqueStocks->add($historiqueStock);
            $historiqueStock->setBottles($this);
        }

        return $this;
    }

    public function removeHistoriqueStock(HistoriqueStock $historiqueStock): static
    {
        if ($this->historiqueStocks->removeElement($historiqueStock)) {
            // set the owning side to null (unless already changed)
            if ($historiqueStock->getBottles() === $this) {
                $historiqueStock->setBottles(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favoris>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favoris $favori): static
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
            $favori->setBottle($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): static
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getBottle() === $this) {
                $favori->setBottle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Wishlist>
     */
    public function getWishlists(): Collection
    {
        return $this->wishlists;
    }

    public function addWishlist(Wishlist $wishlist): static
    {
        if (!$this->wishlists->contains($wishlist)) {
            $this->wishlists->add($wishlist);
            $wishlist->setBottle($this);
        }

        return $this;
    }

    public function removeWishlist(Wishlist $wishlist): static
    {
        if ($this->wishlists->removeElement($wishlist)) {
            // set the owning side to null (unless already changed)
            if ($wishlist->getBottle() === $this) {
                $wishlist->setBottle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setBottle($this);
        }

        return $this;
    }

    public function removeNote(Note $note): static
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getBottle() === $this) {
                $note->setBottle(null);
            }
        }

        return $this;
    }
}
