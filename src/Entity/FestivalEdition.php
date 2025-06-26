<?php

namespace App\Entity;

use App\Repository\FestivalEditionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FestivalEditionRepository::class)]
class FestivalEdition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $year_happened = null;

    #[ORM\Column(length: 100)]
    private ?string $venue_name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 30)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $end_date = null;

    #[ORM\Column]
    private ?int $people_capacity = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $terms_conditions = null;

    #[ORM\Column]
    private ?\DateTime $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYearHappened(): ?int
    {
        return $this->year_happened;
    }

    public function setYearHappened(int $year_happened): static
    {
        $this->year_happened = $year_happened;

        return $this;
    }

    public function getVenueName(): ?string
    {
        return $this->venue_name;
    }

    public function setVenueName(string $venue_name): static
    {
        $this->venue_name = $venue_name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
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

    public function getStartDate(): ?\DateTime
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTime $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTime $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getPeopleCapacity(): ?int
    {
        return $this->people_capacity;
    }

    public function setPeopleCapacity(int $people_capacity): static
    {
        $this->people_capacity = $people_capacity;

        return $this;
    }

    public function getTermsConditions(): ?string
    {
        return $this->terms_conditions;
    }

    public function setTermsConditions(string $terms_conditions): static
    {
        $this->terms_conditions = $terms_conditions;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTime $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
