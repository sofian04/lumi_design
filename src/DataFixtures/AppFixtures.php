<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\OrderItemFactory;
use App\Factory\OrderFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(10);
        CategoryFactory::createMany(5);
        ProductFactory::createMany(100, function () {
            return [
                'category' => CategoryFactory::random(),
            ];
        });
        OrderFactory::createMany(10, function () {
            return [
                'customer' => UserFactory::random(),
            ];
        });
        OrderItemFactory::createMany(30, function () {
            return [
                'product' => ProductFactory::random(),
                'order' => OrderFactory::random(),
            ];
        });
    }
}
