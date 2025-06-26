<?php

namespace App\Entity;

use App\Repository\FestivalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FestivalRepository::class)]
class Festival
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $country = null;

    #[ORM\Column(length: 100)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $street_name = null;

    #[ORM\Column]
    private ?int $street_no = null;

    #[ORM\Column(length: 255)]
    private ?string $festival_contact = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(length: 100)]
    private ?string $logo_url = null;

    #[ORM\Column]
    private ?\DateTime $updated_at = null;

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

    public function getFestivalContact(): ?string
    {
        return $this->festival_contact;
    }

    public function setFestivalContact(string $festival_contact): static
    {
        $this->festival_contact = $festival_contact;

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
}
