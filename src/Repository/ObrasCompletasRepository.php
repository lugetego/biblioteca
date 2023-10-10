<?php

namespace App\Repository;

use App\Entity\ObrasCompletas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ObrasCompletas>
 *
 * @method ObrasCompletas|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObrasCompletas|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObrasCompletas[]    findAll()
 * @method ObrasCompletas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObrasCompletasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObrasCompletas::class);
    }

    public function add(ObrasCompletas $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ObrasCompletas $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ObrasCompletas[] Returns an array of ObrasCompletas objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ObrasCompletas
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
