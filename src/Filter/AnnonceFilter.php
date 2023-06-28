<?php

namespace App\Filter;

use App\Entity\Annonceur;
use App\Entity\Breed;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class AnnonceFilter
{
    public function __construct()
    {
        $this->annonceurs = new ArrayCollection();
        $this->breeds = new ArrayCollection();
    }
    private ?string $search = null;

    /**
     * @var Collection<int, Annonceur>
     */
    private ?Collection $annonceurs = null;
    private ?bool $isLof = null;

    /**
     * @var Collection<int, Breed>
     */
    private ?Collection $breeds = null;

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): self
    {
        $this->search = $search;

        return $this;
    }

    public function isLof(): ?bool
    {
        return $this->isLof;
    }

    public function setIsLof(?bool $isLof): self
    {
        $this->isLof = $isLof;

        return $this;
    }


    /**
     * @return Collection<int, Annonceur>
     */
    public function getAnnonceurs(): ?Collection
    {
        return $this->annonceurs;
    }


    /**
     * @param Collection<int, Annonceur> $annonceurs
     * @return self
     */
    public function setAnnonceurs(?Collection $annonceurs): self
    {
        $this->annonceurs = $annonceurs;

        return $this;
    }

    /**
     * @return Collection<int, Breed>
     */
    public function getBreeds(): ?Collection
    {
        return $this->breeds;
    }

    /**
     * @param Collection<int, Breed> $breeds
     * @return self
     */
    public function setBreeds(?Collection $breeds): self
    {
        $this->breeds = $breeds;

        return $this;
    }
}