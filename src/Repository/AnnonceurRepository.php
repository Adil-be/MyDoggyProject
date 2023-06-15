<?php

namespace App\Repository;

use App\Entity\Annonceur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annonceur>
 *
 * @method Annonceur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonceur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonceur[]    findAll()
 * @method Annonceur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonceur::class);
    }

    public function save(Annonceur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Annonceur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Annonceur[] Returns an array of Annonceur objects
     */
    public function findByRecentAnnonce(): array
    {
        $q = $this->createQueryBuilder('asso');
        $q->leftJoin('asso.annonces', 'a')
            ->orderBy('a.modifiedAt', 'DESC');

        return $q->getQuery()->getResult();
    }

    //    public function findOneBySomeField($value): ?Annonceur
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
