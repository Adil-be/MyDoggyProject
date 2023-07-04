<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use App\Repository\BreedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BreedRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['read:Dog:Item']]
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['read:Dog:Collection']]
        ),
        new Put(
            normalizationContext: ['groups' => ['write:Dog:Item']]
        ),
        new Delete(),
    ]
)]
class Breed
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:Dog:Item', 'read:Dog:Collection', 'read:Breed:Item', 'read:Breed:Collection', 'write:Dog:Item'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:Breed:Item', 'read:Breed:Collection', 'write:Dog:Item'])]
    private ?string $descritpion = null;

    /**
     * @var Collection<int, Dog>
     */
    #[ORM\ManyToMany(targetEntity: Dog::class, inversedBy: 'breeds', cascade: ['persist'])]
    #[Groups(['read:Breed:Item'])]
    private Collection $dogs;

    public function __construct()
    {
        $this->dogs = new ArrayCollection();
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

    public function getDescritpion(): ?string
    {
        return $this->descritpion;
    }

    public function setDescritpion(string $descritpion): self
    {
        $this->descritpion = $descritpion;

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

    public function __toString()
    {
        return $this->getName();
    }
}
