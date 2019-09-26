<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModeDeJeuRepository")
 */
class ModeDeJeu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $joueurMax;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Annonce", mappedBy="modeDeJeu")
     */
    private $annonce;

    public function __construct()
    {
        $this->annonce = new Annonce();
        $this->modeDeJeu = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getJoueurMax(): ?int
    {
        return $this->joueurMax;
    }

    public function setJoueurMax(?int $joueurMax): self
    {
        $this->joueurMax = $joueurMax;

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getModeDeJeu(): Collection
    {
        return $this->modeDeJeu;
    }

    public function addModeDeJeu(Annonce $modeDeJeu): self
    {
        if (!$this->modeDeJeu->contains($modeDeJeu)) {
            $this->modeDeJeu[] = $modeDeJeu;
            $modeDeJeu->setModeDeJeu($this);
        }

        return $this;
    }

    public function removeModeDeJeu(Annonce $modeDeJeu): self
    {
        if ($this->modeDeJeu->contains($modeDeJeu)) {
            $this->modeDeJeu->removeElement($modeDeJeu);
            // set the owning side to null (unless already changed)
            if ($modeDeJeu->getModeDeJeu() === $this) {
                $modeDeJeu->setModeDeJeu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonce(): Collection
    {
        return $this->annonce;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonce->contains($annonce)) {
            $this->annonce[] = $annonce;
            $annonce->setModeDeJeu($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonce->contains($annonce)) {
            $this->annonce->removeElement($annonce);
            // set the owning side to null (unless already changed)
            if ($annonce->getModeDeJeu() === $this) {
                $annonce->setModeDeJeu(null);
            }
        }

        return $this;
    }
}
