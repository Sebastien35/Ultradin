<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Faker\Factory;
use App\Entity\Category;
use Psr\Log\LoggerInterface;

class ProductFixtures extends Fixture
{
    private $managerRegistry;
    private LoggerInterface $logger;

    public function __construct(ManagerRegistry $managerRegistry, LoggerInterface $logger)
    {
        $this->managerRegistry = $managerRegistry;
        $this->logger = $logger;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $rowCount = (int) $manager->getRepository(Product::class)->createQueryBuilder('u')
            ->select('COUNT(u.id_product)')
            ->getQuery()
            ->getSingleScalarResult();

        if ($rowCount > 0) {
            $this->logger->info('Users already exist in the database. No need to create new users.');
            return;
        }

        // Création des produits
        for ($i = 1; $i <= 50; $i++) {
            $product = new Product();
            $product->setName($faker->company . ' SaaS Security');
            $product->setDescription($faker->sentence(10));
            $product->setImageUrl('https://placehold.co/600x400?font=roboto');
            $product->setStock($faker->numberBetween(10, 100));
            $product->setDateCreated($faker->dateTimeThisYear());
            $product->setTechnicalFeatures($faker->paragraph(3));
            $product->setAvailability($faker->boolean());
            $product->setPrice($faker->randomFloat(2, 20, 500));
            $product->setPriceYear($product->getPrice() * 10);

            $manager->persist($product);
        }
        $manager->flush();

        // Création des catégories
        for ($c = 1; $c <= 10; $c++) {
            $category = new Category();
            $category->setName("Category $c");
            $manager->persist($category);
        }
        $manager->flush();

        // Récupérer tous les produits et catégories depuis la base
        $products = $manager->getRepository(Product::class)->findAll();
        $categories = $manager->getRepository(Category::class)->findAll();

        // Associer chaque produit à toutes les catégories
        foreach ($products as $product) {
            foreach ($categories as $category) {
                $product->addCategory($category);
            }
        }
        $manager->flush();
    }

}
