<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50, options: ['default' => 'USER'])]
    private ?string $role = 'USER';

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @var Collection<int, Bouteille>
     */
    #[ORM\OneToMany(targetEntity: Bouteille::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $bouteilles;

    /**
     * @var Collection<int, BouteilleView>
     */
    #[ORM\OneToMany(targetEntity: BouteilleView::class, mappedBy: 'user')]
    private Collection $bouteilleViews;

    #[ORM\OneToOne(targetEntity: Cave::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'cave_id', referencedColumnName: 'id', nullable: true)]
    private ?Cave $cave = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->bouteilles = new ArrayCollection();
        $this->bouteilleViews = new ArrayCollection();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email; // Utiliser l'email comme identifiant
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Méthode utilitaire pour obtenir le nom complet
     */
    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    /**
     * @return Collection<int, Bouteille>
     */
    public function getBouteilles(): Collection
    {
        return $this->bouteilles;
    }

    public function addBouteille(Bouteille $bouteille): static
    {
        if (!$this->bouteilles->contains($bouteille)) {
            $this->bouteilles->add($bouteille);
            $bouteille->setUser($this);
        }

        return $this;
    }

    public function removeBouteille(Bouteille $bouteille): static
    {
        if ($this->bouteilles->removeElement($bouteille)) {
            // set the owning side to null (unless already changed)
            if ($bouteille->getUser() === $this) {
                $bouteille->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BouteilleView>
     */
    public function getBouteilleViews(): Collection
    {
        return $this->bouteilleViews;
    }

    public function addBouteilleView(BouteilleView $bouteilleView): static
    {
        if (!$this->bouteilleViews->contains($bouteilleView)) {
            $this->bouteilleViews->add($bouteilleView);
            $bouteilleView->setUser($this);
        }

        return $this;
    }

    public function removeBouteilleView(BouteilleView $bouteilleView): static
    {
        if ($this->bouteilleViews->removeElement($bouteilleView)) {
            // set the owning side to null (unless already changed)
            if ($bouteilleView->getUser() === $this) {
                $bouteilleView->setUser(null);
            }
        }

        return $this;
    }

    public function getCave(): ?Cave
    {
        return $this->cave;
    }

    public function setCave(Cave $cave): static
    {
        $this->cave = $cave;

        return $this;
    }


}