<?php

namespace App\Repository;

use App\Entity\AdoptionOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdoptionOffer>
 *
 * @method AdoptionOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdoptionOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdoptionOffer[]    findAll()
 * @method AdoptionOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdoptionOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdoptionOffer::class);
    }

    public function save(AdoptionOffer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AdoptionOffer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return AdoptionOffer[] Returns an array of AdoptionOffer objects
     */
    public function findByAdoptantId(int $id): array
    {
        return $this->createQueryBuilder('a')
            ->join("a.adoptant", "u")
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    //    public function findOneBySomeField($value): ?AdoptionOffer
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}