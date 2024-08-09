<?php

namespace App\EntityListener;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsEntityListener(event: 'prePersist', entity: Order::class, method: 'onPrePersist')]
class OrderListener
{
    public function onPrePersist(Order $order, LifecycleEventArgs $args)
    {
        $orderNumber = 'LD-ORD-' . date('Ymdhis') . mt_rand(0, 1000);
        $order->setOrderNumber($orderNumber);
    }
}
