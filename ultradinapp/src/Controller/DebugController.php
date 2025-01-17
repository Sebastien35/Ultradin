<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Completion\Suggestion;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/debug', name: 'app_debug')]
class DebugController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ProductRepository $pr, EntityManagerInterface $em, SerializerInterface $s): Response
    {   
        $product = $pr->findAll()[0];
        $product = $pr->findOneByIdAndReturnSuggestions($product->getIdProduct(), 5);
        $jsonProduct = $s->serialize($product, 'json');
        return new JsonResponse($jsonProduct, 200, [], true);

        



        return new Response(200);


        
    }
}
