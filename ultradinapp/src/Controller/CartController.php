<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cart;
use App\Repository\ProductRepository;

use function PHPUnit\Framework\throwException;

#[Route('/cart', name: 'app_cart_')]
class CartController extends AbstractController
{
    #[Route('/save', name: 'save', methods: ['POST'])]
    public function saveCart(Request $request, EntityManagerInterface $em, ProductRepository $productRepo): JsonResponse{
        $data = json_decode($request->getContent(), true);
        if(!$data){
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        try{
            $cart = new Cart();
            $computedPrice = 0;
            forEach($data['products'] as $product){
                $productId = isset($product['id']) ? $product['id'] : throwException(new \Exception('Product ID is required'));
                
                
                
                
            }
            
        } catch (\Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}



