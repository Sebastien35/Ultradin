<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 50; $i++) {
            $product = new Product();
            $product->setName($faker->company . ' SaaS Security');
            $product->setDescription($faker->sentence(10));
            $product->setImageUrl($faker->imageUrl(320, 240, 'technology', true, 'Cybersecurity'));
            $product->setStock($faker->numberBetween(10, 100));
            $product->setDateCreated($faker->dateTimeThisYear());
            $product->setTechnicalFeatures($faker->paragraph(3));
            $product->setAvailability($faker->boolean());
            $product->setPrice($faker->randomFloat(2, 20, 500));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
