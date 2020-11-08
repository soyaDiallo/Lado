<?php

namespace App\Entity;

use App\Repository\DeclarationTrouveRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeclarationTrouveRepository::class)
 */
class DeclarationTrouve
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRetour;

    /**
     * @ORM\Column(type="text")
     */
    private $motif;

    /**
     * @ORM\OneToOne(targetEntity=Cheque::class, cascade={"persist", "remove"})
     */
    private $cheque;

    /**
     * @ORM\ManyToOne(targetEntity=Beneficiaire::class, inversedBy="declarationTrouves")
     */
    private $beneficiaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->dateRetour;
    }

    public function setDateRetour(\DateTimeInterface $dateRetour): self
    {
        $this->dateRetour = $dateRetour;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getCheque(): ?Cheque
    {
        return $this->cheque;
    }

    public function setCheque(?Cheque $cheque): self
    {
        $this->cheque = $cheque;

        return $this;
    }

    public function getBeneficiaire(): ?Beneficiaire
    {
        return $this->beneficiaire;
    }

    public function setBeneficiaire(?Beneficiaire $beneficiaire): self
    {
        $this->beneficiaire = $beneficiaire;

        return $this;
    }
}
