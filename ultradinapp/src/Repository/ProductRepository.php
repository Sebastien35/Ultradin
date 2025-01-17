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

    public function findByCategoryId($idsCategory, $minPrice = null, $maxPrice = null)
    {
        $entityManager = $this->getEntityManager();

        // Base native SQL
        $sql = "SELECT *
                FROM product
                LEFT JOIN product_category ON product.id_product = product_category.product_id
                WHERE product_category.category_id IN (:idsCategory)";

        // Parameters array
        $params = [
            'idsCategory' => $idsCategory,
        ];

        // Add price conditions
        if ($minPrice !== null && $maxPrice !== null) {
            // Both minPrice and maxPrice
            $sql .= " AND price_month BETWEEN :minPrice AND :maxPrice";
            $params['minPrice'] = $minPrice;
            $params['maxPrice'] = $maxPrice;
        } elseif ($minPrice !== null) {
            // Only minPrice => exact price match
            $sql .= " AND price = :minPrice";
            $params['minPrice'] = $minPrice;
        } elseif ($maxPrice !== null) {
            // Only maxPrice => price up to maxPrice
            $sql .= " AND price <= :maxPrice";
            $params['maxPrice'] = $maxPrice;
        }

        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($entityManager);
        $rsm->addRootEntityFromClassMetadata(\App\Entity\Product::class, 'i');

        // Create the Native Query
        $query = $entityManager->createNativeQuery($sql, $rsm);

        // Bind parameters
        $query->setParameter('idsCategory', $idsCategory, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY);

        // Bind min/max only if they exist
        if ($minPrice !== null) {
            $query->setParameter('minPrice', $minPrice);
        }
        if ($maxPrice !== null) {
            $query->setParameter('maxPrice', $maxPrice);
        }

        // Get results
        return $query->getResult();
    }

    public function findSuggestions(Product $product, $limit = null): array
    {
        $limit = $limit + 1 ?? 3; 
        $entityManager = $this->getEntityManager();
        $categories = $product->getCategory()->toArray();
        $categoriesIds = array_map(fn($category) => $category->getIdCategory(), $categories);

        $fiveProducts = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Product p
            JOIN p.category c
            WHERE c.id_category IN (:categoriesIds)
            AND p.id_product != :productId
            ORDER BY p.date_created DESC'
        )
        ->setParameter('categoriesIds', $categoriesIds)
        ->setParameter('productId', $product->getIdProduct())
        ->setMaxResults($limit)
        ->getResult();

        return array_map(function ($product) {
            return [
                'id' => $product->getIdProduct(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'categories' => $product->getCategory()->toArray(),
                'image_url' => $product->getImageUrl(),
            ];
        }, $fiveProducts);
    }

    public function findOneByIdAndReturnSuggestions($id, $limit = 3)
    {
        $entityManager = $this->getEntityManager();
        $product = $entityManager->find(Product::class, $id);

        if (!$product) {
            return null;
        }

        $suggestions = $this->findSuggestions($product, $limit);
        $arraySuggestions = [];
        foreach ($suggestions as $suggestion) {
            $suggestionObject = [];
            $suggestionObject['id'] = $suggestion['id'];
            $suggestionObject['name'] = $suggestion['name'];
            $suggestionObject['price'] = $suggestion['price'];
            $suggestionObject['image_url'] = $suggestion['image_url'];
        
            $arraySuggestions[] = $suggestionObject;
        }
        
        $data = [
            'id' => $product->getIdProduct(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'categories' => array_map(fn($category) => $category->getName(), $product->getCategory()->toArray()),
            'suggestions' => $arraySuggestions
        ];

        return $data;
        
    }


}
