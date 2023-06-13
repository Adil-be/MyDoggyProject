<?php

namespace App\Entity;

use App\Repository\DogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DogRepository::class)]
class Dog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $antecedant = null;

    #[ORM\Column]
    private ?bool $isAdopted = null;

    #[ORM\Column]
    private ?bool $acceptAnimmals = null;

    #[ORM\OneToMany(mappedBy: 'dog', targetEntity: Image::class)]
    private Collection $images;

    #[ORM\ManyToMany(targetEntity: Breed::class, mappedBy: 'dogs')]
    private Collection $breeds;

    #[ORM\ManyToOne(inversedBy: 'dogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Annonce $annonce = null;

    #[ORM\ManyToMany(targetEntity: AdoptionOffer::class, mappedBy: 'dogs')]
    private Collection $adoptionOffers;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->breeds = new ArrayCollection();
        $this->adoptionOffers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAntecedant(): ?string
    {
        return $this->antecedant;
    }

    public function setAntecedant(?string $antecedant): self
    {
        $this->antecedant = $antecedant;

        return $this;
    }

    public function isIsAdopted(): ?bool
    {
        return $this->isAdopted;
    }

    public function setIsAdopted(bool $isAdopted): self
    {
        $this->isAdopted = $isAdopted;

        return $this;
    }

    public function isAcceptAnimmals(): ?bool
    {
        return $this->acceptAnimmals;
    }

    public function setAcceptAnimmals(bool $acceptAnimmals): self
    {
        $this->acceptAnimmals = $acceptAnimmals;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setDog($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getDog() === $this) {
                $image->setDog(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Breed>
     */
    public function getBreeds(): Collection
    {
        return $this->breeds;
    }

    public function addBreed(Breed $breed): self
    {
        if (!$this->breeds->contains($breed)) {
            $this->breeds->add($breed);
            $breed->addDog($this);
        }

        return $this;
    }

    public function removeBreeds(Breed $breed): self
    {
        if ($this->breeds->removeElement($breed)) {
            $breed->removeDog($this);
        }

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
     * @return Collection<int, AdoptionOffer>
     */
    public function getAdoptionOffers(): Collection
    {
        return $this->adoptionOffers;
    }

    public function addAdoptionOffer(AdoptionOffer $adoptionOffer): self
    {
        if (!$this->adoptionOffers->contains($adoptionOffer)) {
            $this->adoptionOffers->add($adoptionOffer);
            $adoptionOffer->addDog($this);
        }

        return $this;
    }

    public function removeAdoptionOffer(AdoptionOffer $adoptionOffer): self
    {
        if ($this->adoptionOffers->removeElement($adoptionOffer)) {
            $adoptionOffer->removeDog($this);
        }

        return $this;
    }
}
