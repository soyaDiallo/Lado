<?php

namespace App\Entity;

use App\Repository\CompteBancaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompteBancaireRepository::class)
 */
class CompteBancaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numCompte;

    /**
     * @ORM\OneToMany(targetEntity=Chequier::class, mappedBy="compteBancaire")
     */
    private $chequiers;

    /**
     * @ORM\ManyToOne(targetEntity=Agence::class, inversedBy="comptes")
     */
    private $agence;

    public function __construct()
    {
        $this->chequiers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCompte(): ?string
    {
        return $this->numCompte;
    }

    public function setNumCompte(string $numCompte): self
    {
        $this->numCompte = $numCompte;

        return $this;
    }

    /**
     * @return Collection|Chequier[]
     */
    public function getChequiers(): Collection
    {
        return $this->chequiers;
    }

    public function addChequier(Chequier $chequier): self
    {
        if (!$this->chequiers->contains($chequier)) {
            $this->chequiers[] = $chequier;
            $chequier->setCompteBancaire($this);
        }

        return $this;
    }

    public function removeChequier(Chequier $chequier): self
    {
        if ($this->chequiers->removeElement($chequier)) {
            // set the owning side to null (unless already changed)
            if ($chequier->getCompteBancaire() === $this) {
                $chequier->setCompteBancaire(null);
            }
        }

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        $this->agence = $agence;

        return $this;
    }
}
