<?php

namespace App\Repository;

use App\Entity\Bien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bien>
 */
class BienRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bien::class);
    }

    /**
     * Recherche avancée par critères.
     */
    public function search(array $criteria): array
    {
        $qb = $this->createQueryBuilder('b')
            ->innerJoin('b.adresse', 'a')->addSelect('a')
            ->innerJoin('b.caracteristique', 'c')->addSelect('c')
            ->innerJoin('b.agence', 'ag')->addSelect('ag')
            ->leftJoin('b.photos', 'p')->addSelect('p');

        if (!empty($criteria['statut'])) {
            $qb->andWhere('b.statut = :statut')->setParameter('statut', $criteria['statut']);
        } else {
            $qb->andWhere('b.statut IN (:statuts)')
               ->setParameter('statuts', [Bien::STATUT_DISPONIBLE, Bien::STATUT_SOUS_OFFRE]);
        }

        if (!empty($criteria['type'])) {
            $qb->andWhere('c.type = :type')->setParameter('type', $criteria['type']);
        }
        if (!empty($criteria['ville'])) {
            $qb->andWhere('a.ville LIKE :ville')->setParameter('ville', '%' . $criteria['ville'] . '%');
        }
        if (!empty($criteria['prixMin'])) {
            $qb->andWhere('b.prix >= :prixMin')->setParameter('prixMin', $criteria['prixMin']);
        }
        if (!empty($criteria['prixMax'])) {
            $qb->andWhere('b.prix <= :prixMax')->setParameter('prixMax', $criteria['prixMax']);
        }
        if (!empty($criteria['surfaceMin'])) {
            $qb->andWhere('b.surface >= :surfaceMin')->setParameter('surfaceMin', $criteria['surfaceMin']);
        }

        $tri = $criteria['tri'] ?? 'recent';
        match ($tri) {
            'prix_asc' => $qb->orderBy('b.prix', 'ASC'),
            'prix_desc' => $qb->orderBy('b.prix', 'DESC'),
            'surface_asc' => $qb->orderBy('b.surface', 'ASC'),
            'surface_desc' => $qb->orderBy('b.surface', 'DESC'),
            default => $qb->orderBy('b.dateCreation', 'DESC'),
        };

        return $qb->getQuery()->getResult();
    }

    /**
     * Biens similaires : même ville (et si possible même type), excluant le bien courant.
     */
    public function findSimilar(Bien $bien, int $limit = 3): array
    {
        $qb = $this->createQueryBuilder('b')
            ->innerJoin('b.adresse', 'a')->addSelect('a')
            ->innerJoin('b.caracteristique', 'c')->addSelect('c')
            ->leftJoin('b.photos', 'p')->addSelect('p')
            ->where('b.id != :id')
            ->andWhere('a.ville = :ville')
            ->andWhere('b.statut IN (:statuts)')
            ->setParameter('id', $bien->getId())
            ->setParameter('ville', $bien->getAdresse()->getVille())
            ->setParameter('statuts', [Bien::STATUT_DISPONIBLE, Bien::STATUT_SOUS_OFFRE])
            ->setMaxResults($limit)
            ->orderBy('b.dateCreation', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function countByStatut(): array
    {
        $rows = $this->createQueryBuilder('b')
            ->select('b.statut, COUNT(b.id) AS total')
            ->groupBy('b.statut')
            ->getQuery()->getArrayResult();
        $out = [];
        foreach ($rows as $r) {
            $out[$r['statut']] = (int) $r['total'];
        }
        return $out;
    }
}
