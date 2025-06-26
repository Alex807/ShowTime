<?php

namespace App\Entity;

use App\Repository\UserRoleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRoleRepository::class)]
class UserRole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'user_roles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserAccount $userID = null;

    #[ORM\ManyToOne(inversedBy: 'user_roles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $roleID = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserID(): ?UserAccount
    {
        return $this->userID;
    }

    public function setUserID(?UserAccount $userID): static
    {
        $this->userID = $userID;

        return $this;
    }

    public function getRoleID(): ?Role
    {
        return $this->roleID;
    }

    public function setRoleID(?Role $roleID): static
    {
        $this->roleID = $roleID;

        return $this;
    }
}
