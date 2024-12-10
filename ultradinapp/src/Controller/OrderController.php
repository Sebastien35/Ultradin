<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Order;
use Doctrine\ORM\EntityManager;

#[Route('/order', name: 'app_order_')]
class OrderController extends AbstractController
{
    

    

    public function createOrder(Cart $cart, EntityManagerInterface  $em){
        try{
        $newOrder = new Order();
        $newOrder->setDateConfirmed(new \DateTime());
        $newOrder->setOrderUuid(uniqid());
        $newOrder->setTotalPrice($cart->getTotalPrice());
        $newOrder->setStatus('pending');
        $newOrder->setEta(new \DateTime('+2 days'));
        $em->persist($newOrder);
        $em->flush();
        return $newOrder;
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    


}
