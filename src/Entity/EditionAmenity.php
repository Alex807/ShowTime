<?php

namespace App\Entity;

use App\Repository\EditionAmenityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EditionAmenityRepository::class)]
#[ORM\Table(name: "edition_amenity")]
#[ORM\UniqueConstraint(name: "unique_edition_amenity", columns: ["edition_id", "amenity_id"])]
//I want "camping" for example to be listed only once in table,
// resolve the 'more tables' problem with quantity in purchase_amenity
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

    #[ORM\ManyToOne(inversedBy: 'editionAmenities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FestivalEdition $edition = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Amenity $amenity = null;

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

    public function getEdition(): ?FestivalEdition
    {
        return $this->edition;
    }

    public function setEdition(?FestivalEdition $edition): static
    {
        $this->edition = $edition;

        return $this;
    }

    public function getAmenity(): ?Amenity
    {
        return $this->amenity;
    }

    public function setAmenity(?Amenity $amenity): static
    {
        $this->amenity = $amenity;

        return $this;
    }
}
