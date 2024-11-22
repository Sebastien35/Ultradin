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
            'id' => $product->getId(),
        ], Response::HTTP_CREATED);
        } catch (Exception $e){
            return $e->getMessage();
        }
    }


    #[Route('/{id}', name: 'id', requirements: ['id' => '\d+'])]
    public function getProductById(int $id, SerializerInterface $serializer): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
        $jsonContent = $serializer->serialize($product, 'json');
        return new Response($jsonContent, 200, ['Content-Type' => 'application/json']);
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

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function deleteById(int $id): JsonResponse
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
        try {
            $this->entityManager->remove($product);
            $this->entityManager->flush();
            return new JsonResponse(['message' => 'Product deleted successfully'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to delete product', 'details' => $e->getMessage()], 500);
        }
    }



}
