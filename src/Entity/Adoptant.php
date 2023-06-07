<?php

namespace App\Entity;

use App\Entity\User;
use App\Repository\AdoptantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdoptantRepository::class)]
class Adoptant extends User  
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\OneToMany(mappedBy: 'adoptant', targetEntity: AdoptionOffer::class)]
    private Collection $AdoptionOffers;

    public function __construct()
    {
        $this->AdoptionOffers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection<int, AdoptionOffer>
     */
    public function getAdoptionOffers(): Collection
    {
        return $this->AdoptionOffers;
    }

    public function addAdoptionOffer(AdoptionOffer $adoptionOffer): self
    {
        if (!$this->AdoptionOffers->contains($adoptionOffer)) {
            $this->AdoptionOffers->add($adoptionOffer);
            $adoptionOffer->setAdoptant($this);
        }

        return $this;
    }

    public function removeAdoptionOffer(AdoptionOffer $adoptionOffer): self
    {
        if ($this->AdoptionOffers->removeElement($adoptionOffer)) {
            // set the owning side to null (unless already changed)
            if ($adoptionOffer->getAdoptant() === $this) {
                $adoptionOffer->setAdoptant(null);
            }
        }

        return $this;
    }
}
