<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cart;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;

use function PHPUnit\Framework\throwException;

#[Route('/cart', name: 'app_cart_')]
class CartController extends AbstractController
{
    #[Route('/save', name: 'save', methods: ['POST'])]
    public function saveCart(Request $request, EntityManagerInterface $em, ProductRepository $productRepo, CartRepository $cartRepository): JsonResponse{
        $data = json_decode($request->getContent(), true);
        if(!$data){
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        try{
            $id_cart = $data['id_cart'];
            if($cart = $cartRepository->findOneBy(['id_cart' => $id_cart])){
                forEach($data['products'] as $product){
                    $product = $productRepo->find($product['id_product']);
                    $cart->addProduct($product);
                    $cart->setDateUpdated(new \DateTime());
                    $em->persist($cart);
                    $em->flush();
                }
            }else{
                $cart = new Cart();
                $cart->setDateCreated(new \DateTime());
                $cart->setDateUpdated(new \DateTime());
                forEach($data['products'] as $product){
                    $product = $productRepo->find($product['id_product']);
                    $cart->addProduct($product);
                    $em->persist($cart);
                    $em->flush();
                }
            }
        } catch (\Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}



