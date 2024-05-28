<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('email')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\EntityListeners(['App\EntityListener\UserListener'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $fullName = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email()]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column(type: 'json')]
    #[Assert\NotNull()]
    private array $roles = [];

    private ?string $plainPassword = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank()]
    private ?string $password = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Ingredient>
     */
    #[ORM\OneToMany(targetEntity: Ingredient::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $ingredients;

    public function getId(): ?int
    {
        return $this->id;
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

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->ingredients = new ArrayCollection();
        $this->recipes = new ArrayCollection();
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
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
     * Get the value of plainPassword
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * Set the value of plainPassword
     */
    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
            $ingredient->setUser($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): static
    {
        if ($this->ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getUser() === $this) {
                $ingredient->setUser(null);
            }
        }

        return $this;
    }
}
