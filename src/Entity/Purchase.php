<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
#[ORM\Table(name: "purchase")]
class Purchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $total_amount = null;

    #[ORM\Column]
    private ?\DateTime $purchase_date = null;

    #[ORM\ManyToOne(targetEntity: FestivalEdition::class, inversedBy: 'purchases')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?FestivalEdition $edition = null;

    #[ORM\ManyToOne(targetEntity: UserAccount::class, inversedBy: 'purchases')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?UserAccount $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalAmount(): ?string
    {
        return $this->total_amount;
    }

    public function setTotalAmount(string $total_amount): static
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    public function getPurchaseDate(): ?\DateTime
    {
        return $this->purchase_date;
    }

    public function setPurchaseDate(\DateTime $purchase_date): static
    {
        $this->purchase_date = $purchase_date;

        return $this;
    }

    public function getEdition(): ?FestivalEdition
    {
        return $this->edition;
    }

    public function setEdition(?FestivalEdition $edition): static
    {
        $this->edition = $edition;

        return $this;
    }

    public function getUser(): ?UserAccount
    {
        return $this->user;
    }

    public function setUser(?UserAccount $user): static
    {
        $this->user = $user;

        return $this;
    }
}
