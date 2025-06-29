<?php

namespace App\Entity;

use App\Repository\UserRoleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRoleRepository::class)]
#[ORM\Table(name: "user_role")]
class UserRole
{
    #[ORM\Id]  // make this a part of composite primary key
    #[ORM\ManyToOne(inversedBy: 'userRoles')]
    #[ORM\JoinColumn(name: 'user_id', nullable: false, onDelete: 'CASCADE')]
    private ?UserAccount $userAccount = null;

    #[ORM\Id]  // make this a part of composite primary key
    #[ORM\ManyToOne(inversedBy: 'usersWithThisRole')]
    #[ORM\JoinColumn(name: 'role_id', nullable: false, onDelete: 'CASCADE')]
    private ?Role $role = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $sinceDate = null;

    public function getUserAccount(): ?UserAccount
    {
        return $this->userAccount;
    }

    public function setUserAccount(?UserAccount $userAccount): static
    {
        $this->userAccount = $userAccount;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getSinceDate(): ?\DateTimeInterface
    {
        return $this->sinceDate;
    }

    public function setSinceDate(?\DateTimeInterface $sinceDate): static
    {
        $this->sinceDate = $sinceDate;
        return $this;
    }
}
