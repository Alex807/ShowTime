<?php

namespace App\Entity;

use App\Repository\TicketUsageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketUsageRepository::class)]
class TicketUsage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $used_at = null;

    #[ORM\Column(length: 100)]
    private ?string $entry_gate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsedAt(): ?\DateTime
    {
        return $this->used_at;
    }

    public function setUsedAt(\DateTime $used_at): static
    {
        $this->used_at = $used_at;

        return $this;
    }

    public function getEntryGate(): ?string
    {
        return $this->entry_gate;
    }

    public function setEntryGate(string $entry_gate): static
    {
        $this->entry_gate = $entry_gate;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }
}
