<?php

namespace App\Entity;

use App\Repository\ChequeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChequeRepository::class)
 */
class Cheque
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
    private $num;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRefusAdmin;

    /**
     * @ORM\Column(type="float",)
     */
    private $montant;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDeclaration;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datePublication;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRefus;

    /**
     * @ORM\OneToMany(targetEntity=PiecesJointes::class, mappedBy="cheque")
     */
    private $piecesJointes;

    /**
     * @ORM\ManyToOne(targetEntity=Chequier::class, inversedBy="cheques")
     */
    private $chequier;

    /**
     * @ORM\ManyToOne(targetEntity=Beneficiaire::class, inversedBy="cheques")
     */
    private $beneficiaire;

    public function __construct()
    {
        $this->piecesJointes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function setNum(string $num): self
    {
        $this->num = $num;

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

    public function getDateRefusAdmin(): ?string
    {
        return $this->dateRefusAdmin;
    }

    public function setDateRefusAdmin(string $dateRefusAdmin): self
    {
        $this->dateRefusAdmin = $dateRefusAdmin;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateDeclaration(): ?\DateTimeInterface
    {
        return $this->dateDeclaration;
    }

    public function setDateDeclaration(\DateTimeInterface $dateDeclaration): self
    {
        $this->dateDeclaration = $dateDeclaration;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): self
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    public function getDateRefus(): ?\DateTimeInterface
    {
        return $this->dateRefus;
    }

    public function setDateRefus(\DateTimeInterface $dateRefus): self
    {
        $this->dateRefus = $dateRefus;

        return $this;
    }

    /**
     * @return Collection|PiecesJointes[]
     */
    public function getPiecesJointes(): Collection
    {
        return $this->piecesJointes;
    }

    public function addPiecesJointe(PiecesJointes $piecesJointe): self
    {
        if (!$this->piecesJointes->contains($piecesJointe)) {
            $this->piecesJointes[] = $piecesJointe;
            $piecesJointe->setCheque($this);
        }

        return $this;
    }

    public function removePiecesJointe(PiecesJointes $piecesJointe): self
    {
        if ($this->piecesJointes->removeElement($piecesJointe)) {
            // set the owning side to null (unless already changed)
            if ($piecesJointe->getCheque() === $this) {
                $piecesJointe->setCheque(null);
            }
        }

        return $this;
    }

    public function getChequier(): ?Chequier
    {
        return $this->chequier;
    }

    public function setChequier(?Chequier $chequier): self
    {
        $this->chequier = $chequier;

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
