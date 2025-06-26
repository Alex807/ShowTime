<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
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
    #[ORM\OneToMany(targetEntity: UserRole::class, mappedBy: 'roleID', orphanRemoval: true)]
    private Collection $user_roles;

    public function __construct()
    {
        $this->user_roles = new ArrayCollection();
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
    public function getUserRoles(): Collection
    {
        return $this->user_roles;
    }

    public function addUserRole(UserRole $userRole): static
    {
        if (!$this->user_roles->contains($userRole)) {
            $this->user_roles->add($userRole);
            $userRole->setRoleID($this);
        }

        return $this;
    }

    public function removeUserRole(UserRole $userRole): static
    {
        if ($this->user_roles->removeElement($userRole)) {
            // set the owning side to null (unless already changed)
            if ($userRole->getRoleID() === $this) {
                $userRole->setRoleID(null);
            }
        }

        return $this;
    }
}
