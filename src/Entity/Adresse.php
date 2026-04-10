<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
#[ORM\Table(name: 'adresse')]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $rue = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Assert\Regex(pattern: '/^\d{4,10}$/', message: 'Code postal invalide')]
    private ?string $codePostal = null;

    public function getId(): ?int { return $this->id; }
    public function getRue(): ?string { return $this->rue; }
    public function setRue(?string $rue): self { $this->rue = $rue; return $this; }
    public function getVille(): ?string { return $this->ville; }
    public function setVille(?string $ville): self { $this->ville = $ville; return $this; }
    public function getCodePostal(): ?string { return $this->codePostal; }
    public function setCodePostal(?string $codePostal): self { $this->codePostal = $codePostal; return $this; }

    public function __toString(): string
    {
        return trim(($this->rue ?? '') . ', ' . ($this->codePostal ?? '') . ' ' . ($this->ville ?? ''), ', ');
    }
}
