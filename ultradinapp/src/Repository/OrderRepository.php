<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function getOrdersToGenerateSalesReport($dateStart, $dateFin){
        $qb = $this->createQueryBuilder('o')
            ->where('o.createdAt BETWEEN :dateStart AND :dateFin AND o.status != "pending"')
            ->setParameter('dateStart', $dateStart)
            ->setParameter('dateFin', $dateFin)
            ->getQuery()
            ->getResult();
        return $qb;

    }
}
