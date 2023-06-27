<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $modifiedAt = null;

    #[ORM\Column]
    private ?bool $isAvailable = true;

    /**
     * @var Collection<int, Dog>
     */
    #[ORM\OneToMany(mappedBy: 'annonce', targetEntity: Dog::class, cascade: ['persist', 'remove'])]
    private Collection $dogs;

    /**
     * @var Collection<int, AdoptionOffer>
     */
    #[ORM\OneToMany(mappedBy: 'annonce', targetEntity: AdoptionOffer::class, cascade: ['persist', 'remove'])]
    private Collection $adoptionOffers;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    private ?Annonceur $annonceur = null;

    public function __construct()
    {
        $this->dogs = new ArrayCollection();
        $this->adoptionOffers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(\DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function isIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): self
    {
        $this->isAvailable = $isAvailable;

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
            $dog->setAnnonce($this);
        }

        return $this;
    }

    public function removeDog(Dog $dog): self
    {
        if ($this->dogs->removeElement($dog)) {
            // set the owning side to null (unless already changed)
            if ($dog->getAnnonce() === $this) {
                $dog->setAnnonce(null);
            }
        }

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
            $adoptionOffer->setAnnonce($this);
        }

        return $this;
    }

    public function removeAdoptionOffer(AdoptionOffer $adoptionOffer): self
    {
        if ($this->adoptionOffers->removeElement($adoptionOffer)) {
            // set the owning side to null (unless already changed)
            if ($adoptionOffer->getAnnonce() === $this) {
                $adoptionOffer->setAnnonce(null);
            }
        }

        return $this;
    }

    public function getAnnonceur(): ?Annonceur
    {
        return $this->annonceur;
    }

    public function setAnnonceur(?Annonceur $annonceur): self
    {
        $this->annonceur = $annonceur;

        return $this;
    }

    public function getFirstImage(): Image
    {
        foreach ($this->getDogs() as $dog) {
            $firstImage = $dog->getImages()[0];
            if (isset($dog->getImages()[0]) || !is_null($dog->getImages()[0])) {
                $firstImage = $dog->getImages()[0];

                return $firstImage;
            }
        }

        return (new Image())->setPath(Image::PLACEHOLDER);
    }

    /**
     * @return array<int, Image>
     */
    public function getImages()
    {
        $images = [];
        $dogs = $this->getDogs();
        foreach ($dogs as $dog) {
            foreach ($dog->getImages() as $image) {
                $images[] = $image;
            }
        }
        shuffle($images);

        return $images;
    }

    /**
     * @return array<int, Image>
     */
    public function getNImages(int $nbr)
    {
        $nImage = [];
        $images = $this->getImages();
        for ($i = 0; $i < $nbr; ++$i) {
            if (isset($images[$i])) {
                $nImage[] = $images[$i];
            } else {
                $nImage[] = (new Image())->setPath(Image::PLACEHOLDER);
            }
        }

        return $nImage;
    }

    /**
     * @return array<int, Image>
     */
    public function get3Images()
    {
        return $this->getNImages(3);
    }

    /**
     * @return array<int, Image>
     */
    public function get2Images()
    {
        return $this->getNImages(2);
    }

    /**
     * @return array<int, Image>
     */
    public function get5Images()
    {
        return $this->getNImages(5);
    }

    public function numberOfDogs(): int
    {
        return count($this->getDogs());
    }

    public function numberOfAdoptableDog(): int
    {
        return count($this->getAdoptableDog());
    }

    /**
     * @return array<int, Breed>
     */
    public function getBreeds()
    {
        $dogs = $this->getDogs();
        $breeds = [];
        foreach ($dogs as $dog) {
            $tmpBreeds = $dog->getBreeds();
            foreach ($tmpBreeds as $breed) {
                if (!in_array($breed, $breeds, true)) {
                    $breeds[] = $breed;
                }
            }
        }

        return $breeds;
    }

    /**
     * @return Collection<int, Dog>
     */
    public function getAdoptableDog()
    {
        $dogs = $this->getDogs();
        $adoptableDogs = $dogs->filter(function ($d) {
            return !$d->isIsAdopted();
        });

        return $adoptableDogs;
    }

    public function getNbrUnreadMessage(): int
    {
        $nbrUnreadMessage = 0;
        $adoptionOffers = $this->getAdoptionOffers();
        foreach ($adoptionOffers as $adoptionOffer) {
            $adoptionMessages = $adoptionOffer->getMessages()->filter(function ($m) {
                return $m->isFromAdoptant() || $m->isViewed();
            });
            $nbrUnreadMessage += count($adoptionMessages);
        }

        return $nbrUnreadMessage;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}