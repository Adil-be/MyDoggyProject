<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Your subject must be at least {{ limit }} characters long',
        maxMessage: 'Your subject cannot be longer than {{ limit }} characters',
    )]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 100,
        max: 12000,
        minMessage: 'the content must be at least {{ limit }} characters long',
        maxMessage: 'the content name cannot be longer than {{ limit }} characters',
    )]
    private ?string $content = null;

    #[ORM\Column]
    private ?bool $isFromAdoptant = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AdoptionOffer $adoptionOffer = null;

    #[ORM\Column]
    private ?bool $viewed = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function isFromAdoptant(): ?bool
    {
        return $this->isFromAdoptant;
    }

    public function setIsFromAdoptant(bool $isFromAdoptant): self
    {
        $this->isFromAdoptant = $isFromAdoptant;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAdoptionOffer(): ?AdoptionOffer
    {
        return $this->adoptionOffer;
    }

    public function setAdoptionOffer(?AdoptionOffer $adoptionOffer): self
    {
        $this->adoptionOffer = $adoptionOffer;

        return $this;
    }

    public function isViewed(): ?bool
    {
        return $this->viewed;
    }

    public function setViewed(bool $viewed): self
    {
        $this->viewed = $viewed;

        return $this;
    }
}
