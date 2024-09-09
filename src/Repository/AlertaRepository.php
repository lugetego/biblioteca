<?php

namespace App\Repository;

use App\Entity\Alerta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Alerta>
 *
 * @method Alerta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Alerta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Alerta[]    findAll()
 * @method Alerta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlertaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alerta::class);
    }

    public function add(Alerta $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Alerta $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Alerta[] Returns an array of Alerta objects
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

//    public function findOneBySomeField($value): ?Alerta
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    public function findLastAlerta(): ?Alerta
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }


    /**
     * @param string $slug
     * @return Alerta|null
     */
    public function findBySlug(string $slug): ?Alerta
    {
        return $this->findOneBy(['slug' => $slug]);
    }
}
