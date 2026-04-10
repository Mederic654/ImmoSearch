<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use App\Entity\Visite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Visite>
 */
class VisiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visite::class);
    }

    public function findForUser(Utilisateur $u): array
    {
        return $this->createQueryBuilder('v')
            ->innerJoin('v.bien', 'b')->addSelect('b')
            ->innerJoin('b.adresse', 'a')->addSelect('a')
            ->where('v.utilisateur = :u')->setParameter('u', $u)
            ->orderBy('v.dateVisite', 'DESC')
            ->getQuery()->getResult();
    }

    public function findPending(): array
    {
        return $this->createQueryBuilder('v')
            ->innerJoin('v.bien', 'b')->addSelect('b')
            ->innerJoin('v.utilisateur', 'u')->addSelect('u')
            ->where('v.statut = :s')->setParameter('s', Visite::STATUT_DEMANDEE)
            ->orderBy('v.dateVisite', 'ASC')
            ->getQuery()->getResult();
    }

    public function countAll(): int
    {
        return (int) $this->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->getQuery()->getSingleScalarResult();
    }
}
