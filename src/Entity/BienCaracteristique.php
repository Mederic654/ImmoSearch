<?php

namespace App\Entity;

use App\Repository\BienCaracteristiqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BienCaracteristiqueRepository::class)]
#[ORM\Table(name: 'bien_caracteristique')]
class BienCaracteristique
{
    public const TYPES = ['maison', 'appartement', 'terrain', 'local'];
    public const ENERGIES = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
    public const CHAUFFAGES = ['electrique', 'gaz', 'fioul', 'bois', 'pompe a chaleur'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbrPiece = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbrChambre = null;

    #[ORM\Column(nullable: true)]
    private ?bool $balcon = null;

    #[ORM\Column(nullable: true)]
    private ?bool $meuble = null;

    #[ORM\Column(nullable: true)]
    private ?int $etage = null;

    #[ORM\Column(nullable: true)]
    private ?bool $cave = null;

    #[ORM\Column(nullable: true)]
    private ?bool $ascenseur = null;

    #[ORM\Column(nullable: true)]
    private ?bool $jardin = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $energie = null;

    #[ORM\Column(nullable: true)]
    private ?bool $parking = null;

    #[ORM\Column(nullable: true)]
    private ?bool $garage = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $chauffage = null;

    public function getId(): ?int { return $this->id; }
    public function getType(): ?string { return $this->type; }
    public function setType(?string $type): self { $this->type = $type; return $this; }
    public function getNbrPiece(): ?int { return $this->nbrPiece; }
    public function setNbrPiece(?int $nbrPiece): self { $this->nbrPiece = $nbrPiece; return $this; }
    public function getNbrChambre(): ?int { return $this->nbrChambre; }
    public function setNbrChambre(?int $nbrChambre): self { $this->nbrChambre = $nbrChambre; return $this; }
    public function isBalcon(): ?bool { return $this->balcon; }
    public function setBalcon(?bool $v): self { $this->balcon = $v; return $this; }
    public function isMeuble(): ?bool { return $this->meuble; }
    public function setMeuble(?bool $v): self { $this->meuble = $v; return $this; }
    public function getEtage(): ?int { return $this->etage; }
    public function setEtage(?int $v): self { $this->etage = $v; return $this; }
    public function isCave(): ?bool { return $this->cave; }
    public function setCave(?bool $v): self { $this->cave = $v; return $this; }
    public function isAscenseur(): ?bool { return $this->ascenseur; }
    public function setAscenseur(?bool $v): self { $this->ascenseur = $v; return $this; }
    public function isJardin(): ?bool { return $this->jardin; }
    public function setJardin(?bool $v): self { $this->jardin = $v; return $this; }
    public function getEnergie(): ?string { return $this->energie; }
    public function setEnergie(?string $v): self { $this->energie = $v; return $this; }
    public function isParking(): ?bool { return $this->parking; }
    public function setParking(?bool $v): self { $this->parking = $v; return $this; }
    public function isGarage(): ?bool { return $this->garage; }
    public function setGarage(?bool $v): self { $this->garage = $v; return $this; }
    public function getChauffage(): ?string { return $this->chauffage; }
    public function setChauffage(?string $v): self { $this->chauffage = $v; return $this; }
}
