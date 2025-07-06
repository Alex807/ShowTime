<?php

namespace App\Entity;

use App\Repository\UserDetailsRepository;
use App\Validator\SqlInjectionSafe;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserDetailsRepository::class)]
#[ORM\Table(name: "user_details")]
class UserDetails
{
    #[ORM\Id] //this makes the property PK
    #[ORM\OneToOne(targetEntity: UserAccount::class)]
    #[ORM\JoinColumn(referencedColumnName: "id", nullable: false, onDelete: 'CASCADE')] //this makes it to be FK
    private ?UserAccount $user = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "FirstName is required.")]
    #[Assert\Length(max: 255)]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9\s\-\&\.\,\(\)]+$/u",
        message: "FirstName contains invalid characters."
    )]
    #[SqlInjectionSafe]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "LastName is required.")]
    #[Assert\Length(max: 255)]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9\s\-\&\.\,\(\)]+$/u",
        message: "LastName contains invalid characters."
    )]
    #[SqlInjectionSafe]
    private ?string $lastName = null;

    #[ORM\Column]
    #[Assert\Positive]
    private ?int $age = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Length(max: 20)]
    #[Assert\Regex(
        pattern: '\d+',
        message: "Phone can contains only digits."
    )]
    #[SqlInjectionSafe]
    private ?string $phoneNo = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    public function __construct() //set them in backend, user not need to insert data here
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getUser(): ?UserAccount
    {
        return $this->user;
    }

    public function setUser(UserAccount $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getPhoneNo(): ?string
    {
        return $this->phoneNo;
    }

    public function setPhoneNo(?string $phoneNo): static
    {
        $this->phoneNo = $phoneNo;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
