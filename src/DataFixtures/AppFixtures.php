<?php

namespace App\DataFixtures;

use App\Entity\Adresse;
use App\Entity\Agence;
use App\Entity\Bien;
use App\Entity\BienCaracteristique;
use App\Entity\Role;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $em): void
    {
        $roles = [];
        foreach ([Role::ADMIN => 'Administrateur', Role::AGENT => 'Agent immobilier', Role::USER => 'Utilisateur'] as $nom => $desc) {
            $r = (new Role())->setNom($nom)->setDescription($desc);
            $em->persist($r);
            $roles[$nom] = $r;
        }

        $admin = (new Utilisateur())
            ->setEmail('admin@immosearch.test')
            ->setPrenom('Admin')->setNom('Site')
            ->setRole($roles[Role::ADMIN]);
        $admin->setMotDePasse($this->hasher->hashPassword($admin, 'admin1234'));
        $em->persist($admin);

        $agent = (new Utilisateur())
            ->setEmail('agent@immosearch.test')
            ->setPrenom('Alice')->setNom('Durand')
            ->setRole($roles[Role::AGENT]);
        $agent->setMotDePasse($this->hasher->hashPassword($agent, 'agent1234'));
        $em->persist($agent);

        $user = (new Utilisateur())
            ->setEmail('user@immosearch.test')
            ->setPrenom('Paul')->setNom('Martin')
            ->setRole($roles[Role::USER]);
        $user->setMotDePasse($this->hasher->hashPassword($user, 'user1234'));
        $em->persist($user);

        $agences = [];
        foreach ([['Immo Paris', 'paris@immo.test', '0140000000', 'Paris', '75001', '10 rue de Rivoli'],
                  ['Côte Azur Habitat', 'nice@immo.test', '0492000000', 'Nice', '06000', '5 promenade des Anglais'],
                  ['Bordeaux Pierre', 'bdx@immo.test', '0556000000', 'Bordeaux', '33000', '12 cours de l\'Intendance']] as $a) {
            $adr = (new Adresse())->setRue($a[5])->setVille($a[3])->setCodePostal($a[4]);
            $agence = (new Agence())->setNom($a[0])->setEmail($a[1])->setTelephone($a[2])->setAdresse($adr);
            $em->persist($agence);
            $agences[] = $agence;
        }

        $samples = [
            ['Appartement lumineux centre Paris', 'appartement', 'Paris', '75003', '8 rue de Bretagne', 480000, 65, 3, 2, 4, 'B', true, false, true, true, false],
            ['Maison familiale avec jardin', 'maison', 'Bordeaux', '33000', '22 rue Sainte-Catherine', 620000, 140, 6, 4, 0, 'C', false, true, true, true, true],
            ['Studio moderne meublé', 'appartement', 'Nice', '06000', '14 rue Masséna', 195000, 28, 1, 1, 3, 'D', true, true, false, false, false],
            ['Terrain constructible', 'terrain', 'Bordeaux', '33700', 'lieu-dit La Lande', 95000, 800, 0, 0, 0, null, false, false, false, false, false],
            ['Loft atypique', 'appartement', 'Paris', '75011', '3 rue Oberkampf', 720000, 95, 3, 2, 1, 'B', true, false, true, true, false],
            ['Villa vue mer', 'maison', 'Nice', '06200', '7 chemin de la Lanterne', 1450000, 220, 7, 4, 0, 'A', true, false, true, true, true],
        ];

        $statuts = [Bien::STATUT_DISPONIBLE, Bien::STATUT_DISPONIBLE, Bien::STATUT_SOUS_OFFRE, Bien::STATUT_DISPONIBLE, Bien::STATUT_DISPONIBLE, Bien::STATUT_VENDU];

        foreach ($samples as $i => $s) {
            $adr = (new Adresse())->setRue($s[4])->setVille($s[2])->setCodePostal($s[3]);
            $carac = (new BienCaracteristique())
                ->setType($s[1])
                ->setNbrPiece($s[7])
                ->setNbrChambre($s[8])
                ->setEtage($s[9])
                ->setEnergie($s[10])
                ->setBalcon($s[11])
                ->setMeuble($s[12])
                ->setJardin($s[13])
                ->setParking($s[14])
                ->setGarage($s[15])
                ->setChauffage('electrique');

            $bien = (new Bien())
                ->setTitre($s[0])
                ->setDescription('Bien proposé par notre agence. Visite sur rendez-vous. ' . $s[0] . ' situé à ' . $s[2] . '.')
                ->setPrix((string) $s[5])
                ->setSurface($s[6])
                ->setStatut($statuts[$i])
                ->setAgence($agences[$i % count($agences)])
                ->setAdresse($adr)
                ->setCaracteristique($carac);
            $em->persist($bien);
        }

        $em->flush();
    }
}
