<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\Category;
use App\Repository\CategoryRepository;



#[Route('/products', name: 'app_products_')]
class ProductController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/create', name:'create')]
    public function  createProduct(HttpFoundationRequest $request){
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        try{
        $product = new Product();
        $product->setName($data['name']);
        $product->setDescription($data['description'] ?? null);
        $product->setImageUrl($data['image_url'] ?? null);
        $product->setPrice($data['price']);
        $product->setDateCreated(new \DateTime());
        $product->setStock($data['stock']);
        $product->setAvailability($data['availability']);
        $product->setTechnicalFeatures($data['tech_features'] ?? '');
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return $this->json([
            'message' => 'Product created successfully',
            'id' => $product->getIdProduct(),
        ], Response::HTTP_CREATED);
        } catch (Exception $e){
            return $e->getMessage();
        }
    }

    #[Route('/all', name: 'all', methods: ['GET'])]
    public function getAllProducts(SerializerInterface $serializer): JsonResponse
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        if (!$products) {
            return new JsonResponse(['error' => 'No products found'], 404);
        }
        $jsonProducts = $serializer->serialize($products, 'json');
        return new JsonResponse(json_decode($jsonProducts), 200, ['Content-Type' => 'application/json']);
    }


    #[Route('/{id}', name: 'id', requirements: ['id' => '\d+'], methods: ['GET', 'DELETE', 'PUT'])]
    public function getProductById(
        int $id,
        SerializerInterface $serializer,
        HttpFoundationRequest $request,
        AuthorizationCheckerInterface $authorizationChecker,
        ProductRepository $productRepository
    ): Response {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], 404);
        }
        $method = $request->getMethod();
        if($method != 'GET' && !$authorizationChecker->isGranted('ROLE_ADMIN')){
            return new JsonResponse(['error' => 'Access denied'], 403);
        }
        switch ($method) {
            case 'GET':
                $jsonProduct = $serializer->serialize($product, 'json');
                return new JsonResponse(json_decode($jsonProduct), 200, ['Content-Type' => 'application/json']);
            case 'DELETE':
                return $productRepository->deleteProduct($product);
            case 'PUT':
                $data = json_decode($request->getContent(), true);
                return $productRepository->updateProduct($data, $product);
            default:
                return new JsonResponse(['error' => 'Method not allowed'], 405);
        }
    }

    #[Route('/search', name: 'search', methods: ['GET'])]
    public function searchProduct(
        HttpFoundationRequest $request,
        SerializerInterface $serializer
    ): Response {
        $name = $request->query->get('name');
        if (!$name) {
            return new JsonResponse(['error' => 'Name parameter is required'], 400);
        }
        $products = $this->entityManager->getRepository(Product::class)->findBy(['name' => $name]);
        if (!$products) {
            return new JsonResponse(['error' => 'No products found'], 404);
        }
        $jsonProducts = $serializer->serialize($products, 'json');
        return new JsonResponse(json_decode($jsonProducts), 200, ['Content-Type' => 'application/json']);
    }


    
    #[Route('/categories', name: 'categories', methods: ['GET'])]
    public function getProductsByCategories(
        Request $request,
        ProductRepository $productRepository
    ): Response {
        // Retrieve category names from the query string
        $categoryNames = $request->query->all('names'); // Example: ?names[]=category1&names[]=category2
        if (!is_array($categoryNames)) {
            $categoryNames = [$categoryNames];
        }

        if (empty($categoryNames)) {
            return $this->json(['error' => 'No categories provided.'], Response::HTTP_BAD_REQUEST);
        }

        // Fetch products matching all the given categories
        $products = $productRepository->findByCategories($categoryNames);

        if (empty($products)) {
            return $this->json(['error' => 'No products found for the specified categories.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($products);
    }


    

}
