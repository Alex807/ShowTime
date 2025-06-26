<?php

namespace App\Entity;

use App\Repository\EditionReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EditionReviewRepository::class)]
class EditionReview
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $rating_stars = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $comment = null;

    #[ORM\Column]
    private ?\DateTime $posted_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRatingStars(): ?int
    {
        return $this->rating_stars;
    }

    public function setRatingStars(int $rating_stars): static
    {
        $this->rating_stars = $rating_stars;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getPostedAt(): ?\DateTime
    {
        return $this->posted_at;
    }

    public function setPostedAt(\DateTime $posted_at): static
    {
        $this->posted_at = $posted_at;

        return $this;
    }
}
