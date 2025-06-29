<?php

namespace App\Entity;

use App\Repository\UserAccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserAccountRepository::class)]
#[ORM\Table(name: "user_account")]
class UserAccount implements \Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $passwordToken = null;

    // #[ORM\JoinColumn MARKS the owning side of the relation(account has details)
    #[ORM\OneToOne(targetEntity: UserDetails::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: "user_details", referencedColumnName: "id", nullable: false)]
    private ?UserDetails $userDetails = null;

    /**
     * @var Collection<int, UserRole>
     */
    #[ORM\OneToMany(targetEntity: UserRole::class, mappedBy: 'userAccount', orphanRemoval: true)]
    private Collection $userRoles;

    /**
     * @var Collection<int, EditionReview>
     */
    #[ORM\OneToMany(targetEntity: EditionReview::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $editionReviews;

    /**
     * @var Collection<int, Purchase>
     */
    #[ORM\OneToMany(targetEntity: Purchase::class, mappedBy: 'user', orphanRemoval: false)]
    private Collection $purchases;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
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

    public function getUserDetails(): ?UserDetails
    {
        return $this->userDetails;
    }

    public function setUserDetails(?UserDetails $userDetails): static
    {
        $this->userDetails = $userDetails;

        return $this;
    }

    /**
     * @return Collection<int, UserRole>
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(UserRole $userRole): static
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles->add($userRole);
            $userRole->setUserAccount($this);
        }

        return $this;
    }

    public function removeUserRole(UserRole $userRole): static
    {
        if ($this->userRoles->removeElement($userRole)) {
            // set the owning side to null (unless already changed)
            if ($userRole->getUserAccount() === $this) {
                $userRole->setUserAccount(null);
            }
        }

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
}
