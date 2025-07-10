<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
#[ORM\Table(name: "purchase")]
class Purchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: FestivalEdition::class, inversedBy: 'purchases')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?FestivalEdition $edition = null;

    #[ORM\ManyToOne(targetEntity: UserAccount::class, inversedBy: 'purchases')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?UserAccount $user = null;

    #[ORM\Column]
    #[Assert\Positive]
    private ?int $quantity = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?TicketType $ticket_type = null;

    #[ORM\Column]
    private ?\DateTime $purchase_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTicketType(): ?TicketType
    {
        return $this->ticket_type;
    }

    public function setTicketType(?TicketType $ticket_type): static
    {
        $this->ticket_type = $ticket_type;

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
