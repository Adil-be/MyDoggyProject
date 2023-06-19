<?php

namespace App\Entity;

use App\Repository\AnnonceurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnnonceurRepository::class)]
class Annonceur extends User
{
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Annonce>
     */
    #[ORM\OneToMany(mappedBy: 'annonceur', targetEntity: Annonce::class)]
    private Collection $annonces;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
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

    /**
     * @return Collection<int, Annonce>
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces->add($annonce);
            $annonce->setAnnonceur($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getAnnonceur() === $this) {
                $annonce->setAnnonceur(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        // Add role Annonceur to Annonceur
        $roles[] = 'ROLE_ANNONCEUR';

        return array_unique($roles);
    }

    // filter method
    // https://github.com/doctrine/collections/blob/2.1.x/docs/en/index.rst#filter

    public function getNbrAvailableAnnonce(): ?int
    {
        $annonces = $this->getAnnonces();
        $availableAnnonces = $annonces->filter(function ($a) {
            return $a->isIsAvailable();
        });

        return count($availableAnnonces);
    }

    public function getNbrClosedAnnonce(): int
    {
        $annonces = $this->getAnnonces();

        return count($annonces) - $this->getNbrAvailableAnnonce();
    }

    public function __toString()
    {
        return $this->getEmail();
    }
}
