<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/order', name: 'app_order_')]
class OrderController extends AbstractController
{
    

    #[Route('/new', name: 'index', methods: ['POST'])]
    public function createOrder(EntityManagerInterface $em, Order): Response
    {
        
    }
}
