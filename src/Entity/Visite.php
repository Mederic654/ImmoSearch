<?php

namespace App\Entity;

use App\Repository\VisiteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VisiteRepository::class)]
#[ORM\Table(name: 'visite')]
#[ORM\UniqueConstraint(name: 'unique_visite', columns: ['utilisateur_id', 'bien_id', 'date_visite'])]
class Visite
{
    public const STATUT_DEMANDEE = 'demandee';
    public const STATUT_CONFIRMEE = 'confirmee';
    public const STATUT_REFUSEE = 'refusee';
    public const STATUT_EFFECTUEE = 'effectuee';
    public const STATUT_ANNULEE = 'annulee';

    public const STATUTS = [
        self::STATUT_DEMANDEE,
        self::STATUT_CONFIRMEE,
        self::STATUT_REFUSEE,
        self::STATUT_EFFECTUEE,
        self::STATUT_ANNULEE,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'date_visite', type: 'datetime')]
    #[Assert\NotNull]
    #[Assert\GreaterThan('now', message: 'La date doit être dans le futur')]
    private \DateTimeInterface $dateVisite;

    #[ORM\Column(length: 50)]
    #[Assert\Choice(choices: self::STATUTS)]
    private string $statut = self::STATUT_DEMANDEE;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'visites')]
    #[ORM\JoinColumn(nullable: false)]
    private Utilisateur $utilisateur;

    #[ORM\ManyToOne(targetEntity: Bien::class, inversedBy: 'visites')]
    #[ORM\JoinColumn(nullable: false)]
    private Bien $bien;

    public function getId(): ?int { return $this->id; }
    public function getDateVisite(): \DateTimeInterface { return $this->dateVisite; }
    public function setDateVisite(\DateTimeInterface $d): self { $this->dateVisite = $d; return $this; }
    public function getStatut(): string { return $this->statut; }
    public function setStatut(string $statut): self { $this->statut = $statut; return $this; }
    public function getUtilisateur(): Utilisateur { return $this->utilisateur; }
    public function setUtilisateur(Utilisateur $u): self { $this->utilisateur = $u; return $this; }
    public function getBien(): Bien { return $this->bien; }
    public function setBien(Bien $b): self { $this->bien = $b; return $this; }
}
