<?php

namespace App\Entity;

use App\Repository\EditionAmenityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EditionAmenityRepository::class)]
class EditionAmenity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $start_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $end_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTime
    {
        return $this->start_at;
    }

    public function setStartAt(?\DateTime $start_at): static
    {
        $this->start_at = $start_at;

        return $this;
    }

    public function getEndAt(): ?\DateTime
    {
        return $this->end_at;
    }

    public function setEndAt(?\DateTime $end_at): static
    {
        $this->end_at = $end_at;

        return $this;
    }
}
