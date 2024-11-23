<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Exception;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;



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


    #[Route('/{id}', name: 'id', requirements: ['id' => '\d+'], methods: ['GET', 'DELETE', 'PUT'])]
    public function getProductById(
        int $id,
        SerializerInterface $serializer,
        HttpFoundationRequest $request,
        AuthorizationCheckerInterface $authorizationChecker
    ): Response {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], 404);
        }

        switch ($request->getMethod()) {
            case 'GET':
                $jsonProduct = $serializer->serialize($product, 'json');
                return new JsonResponse(json_decode($jsonProduct), 200, ['Content-Type' => 'application/json']);

            case 'DELETE':
                if (!$authorizationChecker->isGranted('ROLE_ADMIN')) {
                    return new JsonResponse(['error' => 'Access denied'], 403);
                }
                $this->entityManager->remove($product);
                $this->entityManager->flush();
                return new JsonResponse(['message' => 'Product deleted successfully'], 200);

            case 'PUT':
                if (!$authorizationChecker->isGranted('ROLE_ADMIN')) {
                    return new JsonResponse(['error' => 'Access denied'], 403);
                }
                $data = json_decode($request->getContent(), true);
                if (!$data) {
                    return new JsonResponse(['error' => 'Invalid JSON'], 400);
                }
                $product->setName($data['name'] ?? $product->getName());
                $product->setDescription($data['description'] ?? $product->getDescription());
                $product->setImageUrl($data['image_url'] ?? $product->getImageUrl());
                $product->setPrice($data['price'] ?? $product->getPrice());
                $product->setStock($data['stock'] ?? $product->getStock());
                $product->setAvailability($data['availability'] ?? $product->isAvailable());
                $product->setTechnicalFeatures($data['tech_features'] ?? $product->getTechnicalFeatures());
                $this->entityManager->persist($product);
                $this->entityManager->flush();
                return new JsonResponse(['message' => 'Product updated successfully'], 200);

            default:
                return new JsonResponse(['error' => 'Method not allowed'], 405);
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

    



}
