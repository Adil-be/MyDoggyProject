<?php

namespace App\Repository;

use App\Entity\Annonce;
use App\Filter\AnnonceFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annonce>
 *
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    public function save(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Annonce[] Returns an array of Annonce objects
     */
    public function findBySomeFilter(AnnonceFilter $filter): array
    {
        $q = $this->createQueryBuilder('a');
        $q->join('a.annonceur', 'asso')
            ->join('a.dogs', 'd')
            ->join('d.breeds', 'b')
            ->andWhere('a.isAvailable = true');

        if (!is_null($filter->getSearch())) {
            $likeStatement = $q->expr()->like('a.title', ':search');
            $q->andWhere($likeStatement);
            $q->setParameter('search', '%' . $filter->getSearch() . '%');
        }
        if (!is_null($filter->getAnnonceurs())) {
            $orStatementsAnnonceur = $q->expr()->orX();
            foreach ($filter->getAnnonceurs() as $key => $annonceur) {
                $id = $annonceur->getId();
                $orStatementsAnnonceur->add(
                    $q->expr()->eq('asso.id', ':idAsso' . $key)
                );
                $q->setParameter('idAsso' . $key, $id);
            }
            $q->andWhere($orStatementsAnnonceur);
        }
        if (!is_null($filter->getBreeds()) || !is_null($filter->isLof())) {
            $q->andWhere('d.isAdopted = false');
        }
        if (!is_null($filter->getBreeds())) {
            $orStatementsBreeds = $q->expr()->orX();
            foreach ($filter->getBreeds() as $key => $breed) {
                $id = $breed->getId();
                $orStatementsBreeds->add(
                    $q->expr()->eq('b.id', ':idBreed' . $key)
                );
                $q->setParameter('idBreed' . $key, $id);
            }
            $q->andWhere($orStatementsBreeds);
        }
        if (!is_null($filter->isLof())) {
            $q->andWhere('d.isLof = :isLof')
                ->setParameter('isLof', $filter->isLof());
        }
        $q->orderBy('a.title', 'ASC');
        // dd($q);
        return $q->getQuery()
            ->getResult();
    }

    //    /**
//     * @return Annonce[] Returns an array of Annonce objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    //    public function findOneBySomeField($value): ?Annonce
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}