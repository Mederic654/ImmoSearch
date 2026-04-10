<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: 'utilisateur')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(name: 'mot_de_passe', length: 255)]
    private string $motDePasse;

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'utilisateurs')]
    #[ORM\JoinColumn(nullable: false)]
    private Role $role;

    #[ORM\OneToMany(targetEntity: Visite::class, mappedBy: 'utilisateur', orphanRemoval: true)]
    private Collection $visites;

    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'utilisateur')]
    private Collection $photos;

    public function __construct()
    {
        $this->visites = new ArrayCollection();
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(?string $prenom): self { $this->prenom = $prenom; return $this; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(?string $nom): self { $this->nom = $nom; return $this; }
    public function getMotDePasse(): string { return $this->motDePasse; }
    public function setMotDePasse(string $motDePasse): self { $this->motDePasse = $motDePasse; return $this; }
    public function getRole(): Role { return $this->role; }
    public function setRole(Role $role): self { $this->role = $role; return $this; }
    public function getVisites(): Collection { return $this->visites; }
    public function getPhotos(): Collection { return $this->photos; }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = [$this->role->getNom()];
        if (!in_array('ROLE_USER', $roles, true)) {
            $roles[] = 'ROLE_USER';
        }
        return array_unique($roles);
    }

    public function getPassword(): string
    {
        return $this->motDePasse;
    }

    public function eraseCredentials(): void
    {
        // nothing sensitive stored on the entity beyond the hash
    }

    public function getFullName(): string
    {
        return trim(($this->prenom ?? '') . ' ' . ($this->nom ?? '')) ?: $this->email;
    }
}
