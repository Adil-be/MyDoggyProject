<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use App\Repository\DogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DogRepository::class)]
#[ApiResource(operations: [
        new Get(
            normalizationContext: ['groups' => ['read:Dog:Item']]
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['read:Dog:Collection']]
        ),
        new Put(
            normalizationContext: ['groups' => ['write:Dog:Item']]
        ),
    ])]
class Dog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:Dog:Collection', 'read:Dog:Item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['read:Annonce:Collection', 'read:Annonce:Item', 'read:Dog:Collection', 'read:Dog:Item', 'write:Dog:Item'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(
        min: 10,
        max: 255,
        minMessage: 'the description must be at least {{ limit }} characters long',
        maxMessage: 'the description cannot be longer than {{ limit }} characters',
    )]
    #[Groups(['read:Dog:Item', 'write:Dog:Item'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        min: 10,
        max: 100,
        minMessage: 'the description must be at least {{ limit }} characters long',
        maxMessage: 'the description cannot be longer than {{ limit }} characters',
    )]
    #[Groups(['read:Dog:Item', 'read:Annonce:Item', 'read:Dog:Collection', 'write:Dog:Item'])]
    private ?string $antecedant = null;

    #[ORM\Column]
    #[Groups(['read:Annonce:Collection', 'read:Annonce:Item', 'read:Dog:Item', 'read:Dog:Collection', 'write:Dog:Item'])]
    private ?bool $isAdopted = false;

    #[ORM\Column]
    #[Groups(['read:Dog:Item', 'read:Annonce:Item', 'read:Dog:Item', 'read:Dog:Collection', 'write:Dog:Item'])]
    private ?bool $acceptAnimmals = null;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(mappedBy: 'dog', targetEntity: Image::class, cascade: ['persist', 'remove'])]
    private Collection $images;

    /**
     * @var Collection<int, Breed>
     */
    #[ORM\ManyToMany(targetEntity: Breed::class, mappedBy: 'dogs', cascade: ['persist'])]
    #[Groups(['read:Dog:Item', 'read:Dog:Collection'])]
    private Collection $breeds;

    #[ORM\ManyToOne(inversedBy: 'dogs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:Dog:Item', 'read:Dog:Collection'])]
    private ?Annonce $annonce = null;

    /**
     * @var Collection<int, AdoptionOffer>
     */
    #[ORM\ManyToMany(targetEntity: AdoptionOffer::class, mappedBy: 'dogs')]
    #[Groups(['read:Dog:Item', 'read:Dog:Collection'])]
    private Collection $adoptionOffers;

    #[ORM\Column]
    #[Groups(['read:Dog:Item', 'read:Dog:Collection', 'read:Annonce:Item'])]
    private ?bool $isLof = null;

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

    public function removeBreed(Breed $breed): self
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

    public function __toString()
    {
        return $this->getName();
    }

    public function isIsLof(): ?bool
    {
        return $this->isLof;
    }

    public function setIsLof(bool $isLof): self
    {
        $this->isLof = $isLof;

        return $this;
    }

    public function getFirstImage(): Image
    {
        return $this->getImages()[0];
    }
}
