<?php

namespace App\Entity;

use App\Repository\BienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BienRepository::class)]
#[ORM\Table(name: 'bien')]
class Bien
{
    public const STATUT_DISPONIBLE = 'disponible';
    public const STATUT_SOUS_OFFRE = 'sous_offre';
    public const STATUT_VENDU = 'vendu';
    public const STATUT_LOUE = 'loue';

    public const STATUTS = [
        self::STATUT_DISPONIBLE,
        self::STATUT_SOUS_OFFRE,
        self::STATUT_VENDU,
        self::STATUT_LOUE,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $titre;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\Positive]
    private string $prix;

    #[ORM\Column]
    #[Assert\Positive]
    private int $surface;

    #[ORM\Column(length: 50)]
    #[Assert\Choice(choices: self::STATUTS)]
    private string $statut = self::STATUT_DISPONIBLE;

    #[ORM\Column(name: 'date_creation', type: 'datetime')]
    private \DateTimeInterface $dateCreation;

    #[ORM\ManyToOne(targetEntity: Agence::class, inversedBy: 'biens')]
    #[ORM\JoinColumn(nullable: false)]
    private Agence $agence;

    #[ORM\OneToOne(targetEntity: Adresse::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Adresse $adresse;

    #[ORM\OneToOne(targetEntity: BienCaracteristique::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'bien_carac_id', nullable: false)]
    private BienCaracteristique $caracteristique;

    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'bien', orphanRemoval: true, cascade: ['persist'])]
    private Collection $photos;

    #[ORM\OneToMany(targetEntity: Visite::class, mappedBy: 'bien', orphanRemoval: true)]
    private Collection $visites;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->photos = new ArrayCollection();
        $this->visites = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getTitre(): string { return $this->titre; }
    public function setTitre(string $titre): self { $this->titre = $titre; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }
    public function getPrix(): string { return $this->prix; }
    public function setPrix(string $prix): self { $this->prix = $prix; return $this; }
    public function getSurface(): int { return $this->surface; }
    public function setSurface(int $surface): self { $this->surface = $surface; return $this; }
    public function getStatut(): string { return $this->statut; }
    public function setStatut(string $statut): self { $this->statut = $statut; return $this; }
    public function getDateCreation(): \DateTimeInterface { return $this->dateCreation; }
    public function setDateCreation(\DateTimeInterface $d): self { $this->dateCreation = $d; return $this; }
    public function getAgence(): Agence { return $this->agence; }
    public function setAgence(Agence $a): self { $this->agence = $a; return $this; }
    public function getAdresse(): Adresse { return $this->adresse; }
    public function setAdresse(Adresse $a): self { $this->adresse = $a; return $this; }
    public function getCaracteristique(): BienCaracteristique { return $this->caracteristique; }
    public function setCaracteristique(BienCaracteristique $c): self { $this->caracteristique = $c; return $this; }
    public function getPhotos(): Collection { return $this->photos; }
    public function getVisites(): Collection { return $this->visites; }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setBien($this);
        }
        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        $this->photos->removeElement($photo);
        return $this;
    }

    public function getPhotoPrincipale(): ?Photo
    {
        return $this->photos->first() ?: null;
    }
}
