<?php

namespace App\Entity;

use App\Repository\ArtistRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
class Artist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $real_name = null;

    #[ORM\Column(length: 100)]
    private ?string $stage_name = null;

    #[ORM\Column(length: 50)]
    private ?string $music_genre = null;

    #[ORM\Column(length: 100)]
    private ?string $instagram_account = null;

    #[ORM\Column(length: 100)]
    private ?string $image_url = null;

    #[ORM\Column(length: 100, nullable: true)]
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
