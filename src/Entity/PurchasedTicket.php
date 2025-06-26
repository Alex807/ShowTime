<?php

namespace App\Entity;

use App\Repository\PurchasedTicketRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchasedTicketRepository::class)]
class PurchasedTicket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?int $tickets_used = null;

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

    public function getTicketsUsed(): ?int
    {
        return $this->tickets_used;
    }

    public function setTicketsUsed(int $tickets_used): static
    {
        $this->tickets_used = $tickets_used;

        return $this;
    }
}
