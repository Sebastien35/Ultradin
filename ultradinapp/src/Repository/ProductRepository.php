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

    public function findSuggestions(Product $product, int $limit = null): array
    {
        $em = $this->getEntityManager();

        // Default limit if none is provided
        if ($limit === null) {
            $limit = 3;
        }

        // Define the ResultSetMapping for the Product entity
        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata(Product::class, 'p');

        // Native SQL query to find products in the same categories as the given product
        $sql = '
            SELECT DISTINCT p.*
            FROM product p
            JOIN product_category pc ON p.id_product = pc.product_id
            WHERE pc.category_id IN (
                SELECT category_id
                FROM product_category
                WHERE product_id = :productId
            )
            AND p.id_product != :productId
            ORDER BY p.date_created DESC
            LIMIT :limit
        ';

        // Create the native query
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter('productId', $product->getIdProduct());
        $query->setParameter('limit', $limit, \PDO::PARAM_INT);

        // Execute the query and get the results
        $suggestedProducts = $query->getResult();

        return $suggestedProducts;
    }


    public function findOneByIdAndReturnSuggestions($id, $limit = null )
    {

        if($limit == null){
            $limit = 3;
        }

        $entityManager = $this->getEntityManager();
        $product = $entityManager->find(Product::class, $id);

        if (!$product) {
            return null;
        }

        $suggestions = $this->getSuggestionsV2($product, $limit);
        $arraySuggestions = [];
        foreach ($suggestions as $suggestion) {
            $suggestionObject = [];
            $suggestionObject['id']         = $suggestion->getIdProduct(); // Utilisation de l'opérateur '->' et de la méthode getter appropriée
            $suggestionObject['name']       = $suggestion->getName(); // Utilisation de la méthode getter appropriée
            $suggestionObject['price']      = $suggestion->getPrice(); // Utilisation de la méthode getter appropriée
            $suggestionObject['image_url']  = $suggestion->getImageUrl(); // Utilisation de la méthode getter appropriée
            $suggestionObject['price_year'] = $suggestion->getPriceYear();

            $arraySuggestions[] = $suggestionObject;
        }
        
        $data = [
            'id' => $product->getIdProduct(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'price_year' => $product->getPriceYear(),
            'image_url' => $product->getImageUrl(),
            'categories' => array_map(fn($category) => $category->getName(), $product->getCategory()->toArray()),
            'suggestions' => $arraySuggestions
        ];

        return $data;
        
    }

    /**
     * @param Product $product
     * @param int $limit
     * @return array
     * 
     * Renvoie les suggestion basés sur les associations entre les produits dans la table panier
     * 
     * @throws \Doctrine\DBAL\Driver\Exception
     * 
     * 
     */
    public function getSuggestionsV2(Product $product, int $limit = null): array
    {
        $em = $this->getEntityManager();
        if ($limit === null) {
            $limit = 3;
        }

        // Définir le mapping des résultats
        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata(Product::class, 'p');

        // Requête SQL native pour trouver les produits fréquemment achetés ensemble
        $sql = '
            SELECT p.*
            FROM cart_product cp1
            JOIN cart_product cp2 ON cp1.cart_id = cp2.cart_id
            JOIN product p ON p.id_product = cp2.product_id
            WHERE cp1.product_id = :productId
            AND cp2.product_id != :productId
            GROUP BY p.id_product
            ORDER BY COUNT(*) DESC
        ';

        // Créer la requête native
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter('productId', $product->getIdProduct());
        $query->setParameter('limit', $limit, \PDO::PARAM_INT);

        // Exécuter la requête et obtenir les résultats
        $suggestedProducts = $query->getResult();

        if (count($suggestedProducts) < $limit ){
            $moreSuggestions = $this->findSuggestions($product, $limit - count($suggestedProducts));
            $suggestedProducts = array_merge($suggestedProducts, $moreSuggestions);
        }

        return $suggestedProducts;
    }

    /**
     * @param int $limit
     * 
     * Renvoie les produits les plus vendus depuis la table product & cart-product
     * 
     **/
    public function getTopSales($limit = 5)
    {
        $entityManager = $this->getEntityManager();
        $query = $query = $entityManager->createQuery(
            'SELECT p FROM App\Entity\Product p
            JOIN p.orders o
            WHERE o.status != :pending AND o.status != :cancelled
            GROUP BY p.id_product
            ORDER BY COUNT(o.id) DESC'
        )
        ->setParameter('pending', 'pending')
        ->setParameter('cancelled', 'cancelled')
        ->setMaxResults($limit);
        return $query->getResult();
    }


}
