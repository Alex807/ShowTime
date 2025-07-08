<?php

namespace App\Entity;

use App\Repository\ArtistRepository;
use App\Validator\SqlInjectionSafe;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
#[ORM\Table(name: "artist")]
#[UniqueEntity(fields: ['real_name', 'stage_name'], message: 'This artist already exists.')]
class Artist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9\s\-\&\.\,\(\)]+$/u",
        message: "Artist real_name contains invalid characters."
    )]
    #[SqlInjectionSafe]
    private ?string $real_name = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9\s\-\&\.\,\(\)]+$/u",
        message: "Artist stage_name contains invalid characters."
    )]
    #[SqlInjectionSafe]
    private ?string $stage_name = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9\s\-\&\.\,\(\)]+$/u",
        message: "Music genre contains invalid characters."
    )]
    #[SqlInjectionSafe]
    private ?string $music_genre = null;

    #[ORM\Column(length: 100)] // always start with '@' as works tagging in Instagram
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9\s\-\&\.\@\,\(\)]+$/u",
        message: "Instagram account contains invalid characters."
    )]
    #[SqlInjectionSafe]
    private ?string $instagram_account = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Url]
    #[SqlInjectionSafe]
    private ?string $image_url = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Email(message: "Invalid email address.")]
    #[SqlInjectionSafe]
    private ?string $manager_email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRealName(): ?string
    {
        return $this->real_name;
    }

    public function setRealName(string $real_name): static
    {
        $this->real_name = $real_name;

        return $this;
    }

    public function getStageName(): ?string
    {
        return $this->stage_name;
    }

    public function setStageName(string $stage_name): static
    {
        $this->stage_name = $stage_name;

        return $this;
    }

    public function getMusicGenre(): ?string
    {
        return $this->music_genre;
    }

    public function setMusicGenre(string $music_genre): static
    {
        $this->music_genre = $music_genre;

        return $this;
    }

    public function getInstagramAccount(): ?string
    {
        return $this->instagram_account;
    }

    public function setInstagramAccount(string $instagram_account): static
    {
        $this->instagram_account = $instagram_account;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): static
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function getManagerEmail(): ?string
    {
        return $this->manager_email;
    }

    public function setManagerEmail(?string $manager_email): static
    {
        $this->manager_email = $manager_email;

        return $this;
    }
}
