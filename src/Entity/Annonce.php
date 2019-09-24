<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnonceRepository")
 */
class Annonce
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
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ModeDeJeu", inversedBy="annonce")
     * @ORM\JoinColumn(nullable=false)
     */
    private $modeDeJeu;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\plateforme", inversedBy="annonce")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plateforme;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cote;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;



    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getCote(): ?int
    {
        return $this->cote;
    }

    public function setCote(?int $cote): self
    {
        $this->cote = $cote;

        return $this;
    }

    public function getModeDeJeu(): ?modeDeJeu
    {
        return $this->modeDeJeu;
    }

    public function setModeDeJeu(?modeDeJeu $modeDeJeu): self
    {
        $this->modeDeJeu = $modeDeJeu;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPlateforme(): ?plateforme
    {
        return $this->plateforme;
    }

    public function setPlateforme(?plateforme $plateforme): self
    {
        $this->plateforme = $plateforme;

        return $this;
    }
}
