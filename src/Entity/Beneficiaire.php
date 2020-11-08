<?php

namespace App\Entity;

use App\Repository\BeneficiaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BeneficiaireRepository::class)
 */
class Beneficiaire extends User
{
    /**
     * @ORM\Id() ORM\@OneToOne(targetEntity="User")
     * ORM\JoinColumn(name="id", referencedColumnName="id")
     **/
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity=Cheque::class, mappedBy="beneficiaire")
     */
    private $cheques;

    /**
     * @ORM\OneToMany(targetEntity=DeclarationTrouve::class, mappedBy="beneficiaire")
     */
    private $declarationTrouves;

    public function __construct()
    {
        $this->cheques = new ArrayCollection();
        $this->declarationTrouves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Cheque[]
     */
    public function getCheques(): Collection
    {
        return $this->cheques;
    }

    public function addCheque(Cheque $cheque): self
    {
        if (!$this->cheques->contains($cheque)) {
            $this->cheques[] = $cheque;
            $cheque->setBeneficiaire($this);
        }

        return $this;
    }

    public function removeCheque(Cheque $cheque): self
    {
        if ($this->cheques->removeElement($cheque)) {
            // set the owning side to null (unless already changed)
            if ($cheque->getBeneficiaire() === $this) {
                $cheque->setBeneficiaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DeclarationTrouve[]
     */
    public function getDeclarationTrouves(): Collection
    {
        return $this->declarationTrouves;
    }

    public function addDeclarationTroufe(DeclarationTrouve $declarationTroufe): self
    {
        if (!$this->declarationTrouves->contains($declarationTroufe)) {
            $this->declarationTrouves[] = $declarationTroufe;
            $declarationTroufe->setBeneficiaire($this);
        }

        return $this;
    }

    public function removeDeclarationTroufe(DeclarationTrouve $declarationTroufe): self
    {
        if ($this->declarationTrouves->removeElement($declarationTroufe)) {
            // set the owning side to null (unless already changed)
            if ($declarationTroufe->getBeneficiaire() === $this) {
                $declarationTroufe->setBeneficiaire(null);
            }
        }

        return $this;
    }
}
