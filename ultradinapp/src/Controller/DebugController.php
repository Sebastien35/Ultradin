<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/debug', name: 'app_debug')]
class DebugController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ProductRepository $pr, EntityManagerInterface $em): Response
    {   
        $product = $pr->findOneBy(['id_product' => 513]);

        echo '<pre>';
        var_dump($product);
        echo '</pre>';

        return new Response(200);


        
    }
}
