<?php

namespace App\Entity;

use App\Repository\PurchasedTicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TicketType $ticket_type = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Purchase $purchase = null;

    /**
     * @var Collection<int, TicketUsage>
     */
    #[ORM\OneToMany(targetEntity: TicketUsage::class, mappedBy: 'purchased_ticket', orphanRemoval: true)]
    private Collection $ticketUsage;

    public function __construct()
    {
        $this->ticketUsage = new ArrayCollection();
    }

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

    public function getTicketType(): ?TicketType
    {
        return $this->ticket_type;
    }

    public function setTicketType(?TicketType $ticket_type): static
    {
        $this->ticket_type = $ticket_type;

        return $this;
    }

    public function getPurchase(): ?Purchase
    {
        return $this->purchase;
    }

    public function setPurchase(?Purchase $purchase): static
    {
        $this->purchase = $purchase;

        return $this;
    }

    /**
     * @return Collection<int, TicketUsage>
     */
    public function getTicketUsage(): Collection
    {
        return $this->ticketUsage;
    }

    public function addTicketUsage(TicketUsage $ticketUsage): static
    {
        if (!$this->ticketUsage->contains($ticketUsage)) {
            $this->ticketUsage->add($ticketUsage);
            $ticketUsage->setPurchasedTicket($this);
        }

        return $this;
    }

    public function removeTicketUsage(TicketUsage $ticketUsage): static
    {
        if ($this->ticketUsage->removeElement($ticketUsage)) {
            // set the owning side to null (unless already changed)
            if ($ticketUsage->getPurchasedTicket() === $this) {
                $ticketUsage->setPurchasedTicket(null);
            }
        }

        return $this;
    }
}
