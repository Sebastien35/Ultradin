<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use PHPUnit\Util\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function updateProduct(array $data, Product $product): JsonResponse
    {
    try {
        if (empty($data['name']) || !isset($data['price'], $data['stock'], $data['availability'])) {
            return new JsonResponse([
                'error' => 'Missing required fields: name, price, stock, or availability',
            ], Response::HTTP_BAD_REQUEST);
        }

        $product->setName($data['name'] ?? $product->getName());
        $product->setDescription($data['description'] ?? $product->getDescription());
        $product->setImageUrl($data['image_url'] ?? $product->getImageUrl());
        $product->setPrice((float) $data['price'] ?? $product->getPrice());
        $product->setStock((int) $data['stock'] ?? $product->getStock());
        $product->setAvailability((bool) $data['availability'] ?? $product->isAvailable());
        $product->setTechnicalFeatures($data['tech_features'] ?? $product->getTechnicalFeatures());
        $product->addCategory($data['category'] ?? $product->getCategory());

        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush();

        return new JsonResponse([
            'message' => 'Product updated successfully',
            'id' => $product->getIdProduct(),
        ], Response::HTTP_OK);

        } catch (Exception $e) {
            return new JsonResponse([
                'error' => 'An error occurred while updating the product: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteProduct(Product $product){
        try{
        $this->getEntityManager()->remove($product);
        $this->getEntityManager()->flush();
        return new JsonResponse([
            'message' => 'Product deleted successfully',
        ], Response::HTTP_OK);
        } catch (Exception $e){
            return new JsonResponse([
                'error' => 'An error occurred while deleting the product: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function findByCategories(array $categories): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Product p
            JOIN p.category c
            WHERE c.name IN (:categories)
            GROUP BY p.id_product
            HAVING COUNT(DISTINCT c.id_category) = :categoryCount'
        )
        ->setParameter('categories', $categories)
        ->setParameter('categoryCount', count($categories));

        $products = $query->getResult();

        return array_map(function ($product) {
            return [
                'id' => $product->getIdProduct(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'categories' => array_map(
                    fn($category) => $category->getName(),
                    $product->getCategory()->toArray()
                ),
            ];
        }, $products);
    }
}
