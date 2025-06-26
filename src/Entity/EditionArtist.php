<?php

namespace App\Entity;

use App\Repository\EditionArtistRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EditionArtistRepository::class)]
class EditionArtist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $is_headliner = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $performance_date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $start_time = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $end_time = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isHeadliner(): ?bool
    {
        return $this->is_headliner;
    }

    public function setIsHeadliner(bool $is_headliner): static
    {
        $this->is_headliner = $is_headliner;

        return $this;
    }

    public function getPerformanceDate(): ?\DateTime
    {
        return $this->performance_date;
    }

    public function setPerformanceDate(\DateTime $performance_date): static
    {
        $this->performance_date = $performance_date;

        return $this;
    }

    public function getStartTime(): ?\DateTime
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTime $start_time): static
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEndTime(): ?\DateTime
    {
        return $this->end_time;
    }

    public function setEndTime(\DateTime $end_time): static
    {
        $this->end_time = $end_time;

        return $this;
    }
}
