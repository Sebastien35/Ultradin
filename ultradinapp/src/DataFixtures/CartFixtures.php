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

class CartFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $products = $manager->getRepository(Product::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();
        

        $now = new \DateTime();
        $interval = new \DateInterval('P7D');
        $startDate = (clone $now)->sub($interval);

        for ($i = 0; $i < count($users); $i++) {
            $user = $users[$i];


            $cart = new Cart();

            $randomTimestampCreated = mt_rand($startDate->getTimestamp(), $now->getTimestamp());
            $randomDateCreated = (new \DateTime())->setTimestamp($randomTimestampCreated);

            $randomTimestampUpdated = mt_rand($randomDateCreated->getTimestamp(), $now->getTimestamp());
            $randomDateUpdated = (new \DateTime())->setTimestamp($randomTimestampUpdated);

            $cart->setDateCreated($randomDateCreated);
            $cart->setDateUpdated($randomDateUpdated);

            for ($j = 0; $j < rand(1, 5); $j++) {
                $cart->addProduct($products[array_rand($products)]);
            }
            $cart->setUser($user);

            $manager->persist($cart);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
        ];
    }
}
