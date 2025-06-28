<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
#[ORM\Table(name: "role")]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    /**
     * @var Collection<int, UserRole>
     */
    #[ORM\OneToMany(targetEntity: UserRole::class, mappedBy: 'role')]
    private Collection $usersWithThisRole;

    public function __construct()
    {
        $this->usersWithThisRole = new ArrayCollection();
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
     * @return Collection<int, UserRole>
     */
    public function getUsersWithThisRole(): Collection
    {
        return $this->usersWithThisRole;
    }

    public function addUsersWithThisRole(UserRole $usersWithThisRole): static
    {
        if (!$this->usersWithThisRole->contains($usersWithThisRole)) {
            $this->usersWithThisRole->add($usersWithThisRole);
            $usersWithThisRole->setRole($this);
        }

        return $this;
    }

    public function removeUsersWithThisRole(UserRole $usersWithRole): static
    {
        if ($this->usersWithThisRole->removeElement($usersWithRole)) {
            // set the owning side to null (unless already changed)
            if ($usersWithRole->getRole() === $this) {
                $usersWithRole->setRole(null);
            }
        }
        return $this;
    }
}
