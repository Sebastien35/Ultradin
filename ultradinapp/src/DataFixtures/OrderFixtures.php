<?php

namespace App\DataFixtures;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Validator\Constraints\Length;
use App\Entity\Order;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {   

        $products = $manager->getRepository(Product::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();
        $faker = Factory::create();
        for ($i = 0; $i < 50; $i++) {
            shuffle($products);
            $order = new Order();
            $order->setDateConfirmed($faker->dateTimeThisYear());
            $order->setOrderUuid($faker->uuid);
            $order->setTotalPrice($faker->randomFloat(2, 20, 500));
            $order->setStatus($faker->randomElement(['pending', 'paid', 'shipped', 'delivered']));
            $order->setEta($faker->optional(0.9, new \DateTime())->dateTimeThisMonth());
            $order->setUser($users[array_rand($users)]);

            $order->addProduct($products[0]);
            if (count($products) > 1) {
                $order->addProduct($products[1]);
            }
            $manager->persist($order);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
            UserFixture::class
        ];
    }
}