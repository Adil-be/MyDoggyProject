<?php

namespace App\Entity;

use App\Repository\AdoptionOfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdoptionOfferRepository::class)]
class AdoptionOffer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $isAccepted = false;

    #[ORM\ManyToOne(inversedBy: 'adoptionOffers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Annonce $annonce = null;

    /**
     * @var Collection<int, Dog>
     */
    #[ORM\ManyToMany(targetEntity: Dog::class, inversedBy: 'adoptionOffers', cascade: ['persist'])]
    private Collection $dogs;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(mappedBy: 'adoptionOffer', targetEntity: Message::class, cascade: ['persist', 'remove'])]
    #[Assert\Valid]
    private Collection $messages;

    #[ORM\ManyToOne(inversedBy: 'adoptionOffers')]
    #[Assert\Valid]
    private ?Adoptant $adoptant = null;

    public function __construct()
    {
        $this->dogs = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function isAccepted(): ?bool
    {
        return $this->isAccepted;
    }

    public function setIsAccepted(bool $isAccepted): self
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): self
    {
        $this->annonce = $annonce;

        return $this;
    }

    /**
     * @return Collection<int, Dog>
     */
    public function getDogs(): Collection
    {
        return $this->dogs;
    }

    public function addDog(Dog $dog): self
    {
        if (!$this->dogs->contains($dog)) {
            $this->dogs->add($dog);
        }

        return $this;
    }

    public function removeDog(Dog $dog): self
    {
        $this->dogs->removeElement($dog);

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setAdoptionOffer($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getAdoptionOffer() === $this) {
                $message->setAdoptionOffer(null);
            }
        }

        return $this;
    }

    public function getAdoptant(): ?Adoptant
    {
        return $this->adoptant;
    }

    public function setAdoptant(?Adoptant $adoptant): self
    {
        $this->adoptant = $adoptant;

        return $this;
    }

    public function nbrNewMessageAdoptant(): int
    {
        $newMessageFromAdoptant = $this->getMessages()->filter(function ($m) {
            return $m->isFromAdoptant() && !$m->isViewed();
        });

        return count($newMessageFromAdoptant);
    }

    public function nbrNewMessageAnnonceur(): int
    {
        $newMessageFromAdoptant = $this->getMessages()->filter(function ($m) {
            return !$m->isFromAdoptant() && !$m->isViewed();
        });

        return count($newMessageFromAdoptant);
    }

    public function __toString()
    {
        return 'adoptionOffer';
    }
}
