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

    public function findOrderWithRelations($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.customer', 'c')
            ->leftJoin('o.orderItems', 'oi')
            ->leftJoin('oi.product', 'p')
            ->where('o.id = :id')
            ->setParameter(':id', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
