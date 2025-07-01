<?php

namespace App\Entity;

use App\Repository\FestivalEditionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FestivalEditionRepository::class)]
#[ORM\Table(name: "festival_edition")]
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

    #[ORM\ManyToOne(targetEntity: Festival::class, inversedBy: 'festivalEditions')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Festival $festival = null;

    /**
     * @var Collection<int, EditionReview>
     */
    #[ORM\OneToMany(targetEntity: EditionReview::class, mappedBy: 'edition', cascade: ['remove'], orphanRemoval: true)]
    private Collection $editionReviews;

    /**
     * @var Collection<int, EditionArtist>
     */
    #[ORM\OneToMany(targetEntity: EditionArtist::class, mappedBy: 'edition', cascade: ['remove'], orphanRemoval: true)]
    private Collection $editionArtists;

    /**
     * @var Collection<int, EditionAmenity>
     */
    #[ORM\OneToMany(targetEntity: EditionAmenity::class, mappedBy: 'edition', cascade: ['remove'], orphanRemoval: true)]
    private Collection $editionAmenities;

    /**
     * @var Collection<int, Purchase>
     */
    #[ORM\OneToMany(targetEntity: Purchase::class, mappedBy: 'edition',  cascade: ['remove'], orphanRemoval: true)]
    private Collection $purchases;

    public function __construct()
    {
        $this->editionReviews = new ArrayCollection();
        $this->editionArtists = new ArrayCollection();
        $this->editionAmenities = new ArrayCollection();
        $this->purchases = new ArrayCollection();
    }

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

    public function getFestival(): ?Festival
    {
        return $this->festival;
    }

    public function setFestival(?Festival $festival): static
    {
        $this->festival = $festival;

        return $this;
    }

    /**
     * @return Collection<int, EditionReview>
     */
    public function getEditionReviews(): Collection
    {
        return $this->editionReviews;
    }

    public function addEditionReview(EditionReview $editionReview): static
    {
        if (!$this->editionReviews->contains($editionReview)) {
            $this->editionReviews->add($editionReview);
            $editionReview->setEdition($this);
        }

        return $this;
    }

    public function removeEditionReview(EditionReview $editionReview): static
    {
        if ($this->editionReviews->removeElement($editionReview)) {
            // set the owning side to null (unless already changed)
            if ($editionReview->getEdition() === $this) {
                $editionReview->setEdition(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EditionArtist>
     */
    public function getEditionArtists(): Collection
    {
        return $this->editionArtists;
    }

    public function addEditionArtist(EditionArtist $editionArtist): static
    {
        if (!$this->editionArtists->contains($editionArtist)) {
            $this->editionArtists->add($editionArtist);
            $editionArtist->setEdition($this);
        }

        return $this;
    }

    public function removeEditionArtist(EditionArtist $editionArtist): static
    {
        if ($this->editionArtists->removeElement($editionArtist)) {
            // set the owning side to null (unless already changed)
            if ($editionArtist->getEdition() === $this) {
                $editionArtist->setEdition(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EditionAmenity>
     */
    public function getEditionAmenities(): Collection
    {
        return $this->editionAmenities;
    }

    public function addEditionAmenity(EditionAmenity $editionAmenity): static
    {
        if (!$this->editionAmenities->contains($editionAmenity)) {
            $this->editionAmenities->add($editionAmenity);
            $editionAmenity->setEdition($this);
        }

        return $this;
    }

    public function removeEditionAmenity(EditionAmenity $editionAmenity): static
    {
        if ($this->editionAmenities->removeElement($editionAmenity)) {
            // set the owning side to null (unless already changed)
            if ($editionAmenity->getEdition() === $this) {
                $editionAmenity->setEdition(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Purchase>
     */
    public function getPurchases(): Collection
    {
        return $this->purchases;
    }

    public function addPurchase(Purchase $purchase): static
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases->add($purchase);
            $purchase->setEdition($this);
        }

        return $this;
    }

    public function removePurchase(Purchase $purchase): static
    {
        if ($this->purchases->removeElement($purchase)) {
            // set the owning side to null (unless already changed)
            if ($purchase->getEdition() === $this) {
                $purchase->setEdition(null);
            }
        }

        return $this;
    }
}
