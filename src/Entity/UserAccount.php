<?php

namespace App\Entity;

use App\Repository\UserAccountRepository;
use App\Validator\SqlInjectionSafe;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserAccountRepository::class)]
#[ORM\Table(name: "user_account")]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email.')]
class UserAccount implements UserInterface, PasswordAuthenticatedUserInterface
{
    private const LOWEST_ROLE_IN_HIERARCHY = ['ROLE_USER'];
    private const ROLE_WHO_PROMOTES = 'ROLE_ADMIN';

    #[ORM\Id] //this makes the property PK
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    #[SqlInjectionSafe]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[SqlInjectionSafe]
    private ?string $password = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    #[SqlInjectionSafe]
    private ?string $passwordToken = null;


    /**
     * @var Collection<int, EditionReview>
     */
    #[ORM\OneToMany(targetEntity: EditionReview::class, mappedBy: 'user')]
    private Collection $editionReviews;

    /**
     * @var Collection<int, Purchase>
     */
    #[ORM\OneToMany(targetEntity: Purchase::class, mappedBy: 'user')]
    private Collection $purchases;

    public function __construct()
    {
        $this->setRoles(self::LOWEST_ROLE_IN_HIERARCHY);
        $this->editionReviews = new ArrayCollection();
        $this->purchases = new ArrayCollection();
    }

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPasswordToken(): ?string
    {
        return $this->passwordToken;
    }

    public function setPasswordToken(?string $passwordToken): static
    {
        $this->passwordToken = $passwordToken;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

        public function setRoles(array $roles): self
    {
        $aux = $this->roles;
        $this->roles = array_unique(array_merge($aux, $roles));
        return $this;
    }

    /**
     * @return Collection<int, EditionReview>
     */
    public function getEditionReviews(): Collection
    {
        return $this->editionReviews;
    }

    public function addEditionReview(EditionReview $editionReview): static
    {
        if (!$this->editionReviews->contains($editionReview)) {
            $this->editionReviews->add($editionReview);
            $editionReview->setUser($this);
        }

        return $this;
    }

    public function removeEditionReview(EditionReview $editionReview): static
    {
        if ($this->editionReviews->removeElement($editionReview)) {
            // set the owning side to null (unless already changed)
            if ($editionReview->getUser() === $this) {
                $editionReview->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Purchase>
     */
    public function getPurchases(): Collection
    {
        return $this->purchases;
    }

    public function addPurchase(Purchase $purchase): static
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases->add($purchase);
            $purchase->setUser($this);
        }

        return $this;
    }

    public function removePurchase(Purchase $purchase): static
    {
        if ($this->purchases->removeElement($purchase)) {
            // set the owning side to null (unless already changed)
            if ($purchase->getUser() === $this) {
                $purchase->setUser(null);
            }
        }

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials(): void {}
    //in this method delete only sensitive data, not registration ones
}
