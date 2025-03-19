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

    public function getOrdersToGenerateSalesReport($dateStart, $dateFin) {
        $qb = $this->createQueryBuilder('o')
            ->where('o.date_confirmed BETWEEN :dateStart AND :dateFin AND o.status != :status')
            ->setParameter('dateStart', $dateStart)
            ->setParameter('dateFin', $dateFin)
            ->setParameter('status', 'pending')
            ->getQuery()
            ->getResult();
    
        return $qb;
    }
}
