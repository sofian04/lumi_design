<?php

namespace App\Factory;

use App\Entity\Order;
use App\Enum\OrderStatus;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Order>
 */
final class OrderFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct() {}

    public static function class(): string
    {
        return Order::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            // 'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-3 years')),
            'orderNumber' => '123',
            'paidAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-3 years')),
            'customer' => UserFactory::new(),
            'status' => self::faker()->randomElement(OrderStatus::cases()),
            'total' => 2
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Order $order): void {})
        ;
    }
}
