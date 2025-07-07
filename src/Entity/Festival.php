<?php

namespace App\Entity;

use App\Repository\FestivalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\SqlInjectionSafe;

#[ORM\Entity(repositoryClass: FestivalRepository::class)]
#[ORM\Table(name: "festival")]
#[UniqueEntity(fields: ['name'], message: 'This festival already exists.')]
class Festival
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9\s\-\&\.\,\(\)]+$/u",
        message: "Name contains invalid characters."
    )]
    #[SqlInjectionSafe]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9\s\-\&\.\,\(\)]+$/u",
        message: "Name contains invalid characters."
    )]
    #[SqlInjectionSafe]
    private ?string $country = null;

    #[ORM\Column(length: 100)]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9\s\-\,\(\)]+$/u",
        message: "City contains invalid characters."
    )]
    #[SqlInjectionSafe]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9\s\-\&\.\,\(\)]+$/u",
        message: "Street_Name contains invalid characters."
    )]
    #[SqlInjectionSafe]
    private ?string $street_name = null;

    #[ORM\Column]
    #[Assert\Positive]
    private ?int $street_no = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email(message: "Invalid email address.")]
    #[SqlInjectionSafe]
    private ?string $festival_email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url]
    #[SqlInjectionSafe]
    private ?string $website = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Url]
    #[SqlInjectionSafe]
    private ?string $logo_url = null;

    #[ORM\Column]
    private ?\DateTime $updated_at = null;

    /**
     * @var Collection<int, FestivalEdition>
     */
    #[ORM\OneToMany(targetEntity: FestivalEdition::class, mappedBy: 'festival')]
    private Collection $festivalEditions;

    public function __construct()
    {
        $this->festivalEditions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->street_name;
    }

    public function setStreetName(string $street_name): static
    {
        $this->street_name = $street_name;

        return $this;
    }

    public function getStreetNo(): ?int
    {
        return $this->street_no;
    }

    public function setStreetNo(?int $street_no): static
    {
        $this->street_no = $street_no;

        return $this;
    }

    public function getFestivalEmail(): ?string
    {
        return $this->festival_email;
    }

    public function setFestivalEmail(string $festival_email): static
    {
        $this->festival_email = $festival_email;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getLogoUrl(): ?string
    {
        return $this->logo_url;
    }

    public function setLogoUrl(string $logo_url): static
    {
        $this->logo_url = $logo_url;

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

    /**
     * @return Collection<int, FestivalEdition>
     */
    public function getFestivalEditions(): Collection
    {
        return $this->festivalEditions;
    }

    public function addFestivalEdition(FestivalEdition $festivalEdition): static
    {
        if (!$this->festivalEditions->contains($festivalEdition)) {
            $this->festivalEditions->add($festivalEdition);
            $festivalEdition->setFestival($this);
        }

        return $this;
    }

    public function removeFestivalEdition(FestivalEdition $festivalEdition): static
    {
        if ($this->festivalEditions->removeElement($festivalEdition)) {
            // set the owning side to null (unless already changed)
            if ($festivalEdition->getFestival() === $this) {
                $festivalEdition->setFestival(null);
            }
        }

        return $this;
    }
}
