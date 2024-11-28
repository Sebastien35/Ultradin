<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Util\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;



/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function listAll(): JsonResponse
    {
        $categories = $this->findAll();
        $data = [];

        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->getIdCategory(),
                'name' => $category->getName(),
                'nb_products' => count($category->getProducts()),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function getCategoryByIdCategory($id_category): JsonResponse
    {
        $category = $this->find($id_category);

        if (!$category) {
            return new JsonResponse([
                'error' => 'Category not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $category->getIdCategory(),
            'name' => $category->getName(),
            'nb_products' => count($category->getProducts()),
        ], Response::HTTP_OK);
    }

    public function deleteCategory(int $id){
        try{
            $category = $this->find($id);
            if (!$category) {
                return new JsonResponse([
                    'error' => 'Category not found',
                ], Response::HTTP_NOT_FOUND);
            }
            $this->getEntityManager()->remove($category);
            $this->getEntityManager()->flush();
            return new JsonResponse([
                'message' => 'Category deleted successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'An error occurred while deleting the category: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCategory(Category $category, $data): JsonResponse
    {
        try{
            $category->setName($data['name'] ?? $category->getName());
            $this->getEntityManager()->persist($category);
            $this->getEntityManager()->flush();
            return new JsonResponse([
                'message' => 'Category updated successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'An error occurred while updating the category: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function findByName(string $name){
        return $this->findOneBy(['name' => $name]);
    }
}