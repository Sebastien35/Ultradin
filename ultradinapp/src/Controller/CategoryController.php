<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/categories', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index', methods:'GET')]
    public function index(CategoryRepository $categoryRepository): JsonResponse 
    {
        return $categoryRepository->listAll();
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em): JsonResponse
    {   
        if(!$this->isGranted('ROLE_ADMIN')){
            return new JsonResponse([
                'error' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);
        if (empty($data['name'])) {
            return new JsonResponse([
                'error' => 'Name is required',
            ], Response::HTTP_BAD_REQUEST);
        }
        $category = new Category();
        $category->setName($data['name']);
        $em->persist($category);
        $em->flush();
        return new JsonResponse([
            'id' => $category->getIdCategory(),
            'name' => $category->getName(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/{name}', name: 'show', methods: ['GET', 'PUT', 'DELETE'])]
    public function show(CategoryRepository $categoryRepository, $name, Request $request): JsonResponse
    {   
        $category = $categoryRepository->findByName($name);
        if (!$category) {
            return new JsonResponse([
                'error' => 'Category not found',
            ], Response::HTTP_NOT_FOUND);
        }
        
        $id = $category->getIdCategory();
        $method = $request->getMethod();
        if($method != 'GET'){
            if(!$this->isGranted('ROLE_ADMIN')){
                return new JsonResponse([
                    'error' => 'Unauthorized',
                ], Response::HTTP_UNAUTHORIZED);
            }
        }
        switch ($method ){
            case 'GET':
                return $categoryRepository->getCategoryByIdCategory($id);
            case 'PUT':
                $data = json_decode($request->getContent(), true);
                return $categoryRepository->updateCategory($id, $data);
            case 'DELETE':
                return $categoryRepository->deleteCategory($id);
            default:
                return new JsonResponse([
                    'error' => 'Method not allowed',
                ], Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }



    
}
