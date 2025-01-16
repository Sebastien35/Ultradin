<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
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
            $product->setPriceYear($product->getPrice() * 10);

            $manager->persist($product);
        }

        $manager->flush();

        for($i = 1; $i <= 50; $i++){
            for($c = 1; $c <= 10; $c++){
                $connection = $this->managerRegistry->getConnection();
                $connection->executeStatement('INSERT INTO product_category (product_id, category_id) VALUES (:product_id, :category_id)', [
                    'product_id' => $i,
                    'category_id' => $c
                ]);
            }
            
        }
    }
}
