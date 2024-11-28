<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/{id}', name: 'user', methods: ['GET', 'PUT', 'DELETE'])]
    public function handle(
        int $id, 
        EntityManagerInterface $em, 
        Request $request, 
        UserRepository $userRepo,
        AuthorizationCheckerInterface $authorizationChecker
    ): JsonResponse {
        $user = $userRepo->find($id);
        if (!$user) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $loggedInUser = $this->getUser();
        if (!$loggedInUser) {
            return $this->json(['error' => 'Authentication required'], Response::HTTP_UNAUTHORIZED);
        }

        $method = $request->getMethod();
        $isAdmin = $authorizationChecker->isGranted('ROLE_ADMIN');
        $isOwner = $user->getId() === $loggedInUser['id'];

        if (!$isAdmin && !$isOwner) {
            return $this->json(['error' => 'Access denied'], Response::HTTP_FORBIDDEN);
        }
        switch($method){
            case 'GET':
                return $this->json($user);
            case 'PUT':
                $data = json_decode($request->getContent(), true);
                if (!$data) {
                    return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
                }
                try{
                    $userRepo->updateUser($user, $data);
                    return $this->json(['message' => 'User updated successfully']);
                } catch (\Exception $e) {
                    return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
                }
            case 'DELETE':
                try{
                    $em->remove($user);
                    $em->flush();
                    return $this->json(['message' => 'User deleted successfully']);
                } catch (\Exception $e) {
                    return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
                }

        }
    }
}
