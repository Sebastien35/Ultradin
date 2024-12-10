<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;


#[Route('/cart', name: 'app_cart_')]
class CartController extends AbstractController
{
    #[Route('/save', name: 'save', methods: ['POST'])]
    public function saveCart(Request $request): JsonResponse
    {
        $data = $request->getContent();
        $cart = json_decode($data, true);

        if (!isset($cart['products'])) {
            return new JsonResponse(['error' => 'Products are required.'], Response::HTTP_BAD_REQUEST);
        }
    }   
}
