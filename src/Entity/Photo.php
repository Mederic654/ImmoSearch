<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
#[ORM\Table(name: 'photo')]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $url;

    #[ORM\ManyToOne(targetEntity: Bien::class, inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private Bien $bien;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private Utilisateur $utilisateur;

    public function getId(): ?int { return $this->id; }
    public function getUrl(): string { return $this->url; }
    public function setUrl(string $url): self { $this->url = $url; return $this; }
    public function getBien(): Bien { return $this->bien; }
    public function setBien(Bien $b): self { $this->bien = $b; return $this; }
    public function getUtilisateur(): Utilisateur { return $this->utilisateur; }
    public function setUtilisateur(Utilisateur $u): self { $this->utilisateur = $u; return $this; }
}
