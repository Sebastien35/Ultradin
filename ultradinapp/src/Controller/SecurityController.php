<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Service\EncryptionService;

class SecurityController extends AbstractController
{

    private UserPasswordHasherInterface $passwordHasher;
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->jwtManager = $jwtManager;
    }


    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, UserProviderInterface $userProvider): JsonResponse
    {
        $data       = json_decode($request->getContent(), true);
        $email      = $data['email'] ?? null;
        $password   = $data['password'] ?? null;

        if (!$email || !$password) {
            return new JsonResponse(['error' => 'Email and password are required.'], JsonResponse::HTTP_BAD_REQUEST);
        }
        try {
            /** @var UserInterface|PasswordAuthenticatedUserInterface $user */
            $user = $userProvider->loadUserByIdentifier($email);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid login credentials.'], JsonResponse::HTTP_UNAUTHORIZED);
        }
        if (!$this->passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(['error' => 'Invalid login credentials.'], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $token = $this->jwtManager->create($user);
        return new JsonResponse(['token' => $token]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (empty($data['email']) || empty($data['password'])) {
            return new JsonResponse(['error' => 'Email and password are required'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return new JsonResponse(['error' => 'User already exists'], JsonResponse::HTTP_CONFLICT);
        }

        $user = new User();
        
        $user->setEmail($data['email']);
        $user->setRoles(['ROLE_USER']);
        $user->setCreatedAt(new \DateTime());
        $user->setPhone($data['phone']);
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);
        $user->setDefaultPaymentMethod($data['default_payment_method']);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'User registered successfully'], JsonResponse::HTTP_CREATED);
    }

    #[Route(path: '/refresh', name: 'app_refresh', methods: ['POST'])]
    public function refresh(request $request): JsonResponse
    {
        $refreshToken = $request->get('refresh_token');
        if (!$refreshToken) {
            return new JsonResponse(['error' => 'Refresh token is required'], 400);
        }
        $user = $this->getUser();
        if(!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }
        $newJwt = $this->jwtManager->create($user);
        return new JsonResponse(['token' => $newJwt]);
    }

    

}
