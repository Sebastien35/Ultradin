<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Psr\Log\LoggerInterface;

class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private LoggerInterface $logger;

    public function __construct(UserPasswordHasherInterface $passwordHasher, LoggerInterface $logger)
    {
        $this->passwordHasher = $passwordHasher;
        $this->logger = $logger;
    }

    public function load(ObjectManager $manager): void
    {
        $userData = [
            ["email" => "admin@email.com", 'password' => 'password', 'phone' => '0123456789', 'roles' => ['ROLE_ADMIN']],
            ['email' => "user@email.com", 'password' => 'password', 'phone' => '9876543210', 'roles' => ['ROLE_USER']]
        ];
        
        foreach ($userData as $ud) {
            try {
                $user = new User();
                $user->setEmail($ud['email']);
                $user->setPhone($ud['phone']);
                $user->setRoles($ud['roles']); // Pass roles as an array
                $user->setPassword($this->passwordHasher->hashPassword($user, $ud['password']));
                $user->setCreatedAt(new \DateTimeImmutable());
                $manager->persist($user);
        
                $this->logger->info('User created successfully', [
                    'email' => $ud['email'],
                    'roles' => $ud['roles']
                ]);
            } catch (\Exception $e) {
                $this->logger->error('Error creating user', [
                    'email' => $ud['email'],
                    'error' => $e->getMessage()
                ]);
            }
        }

        try {
            $manager->flush();
            $this->logger->info('All users have been persisted successfully.');
        } catch (\Exception $e) {
            $this->logger->critical('Error persisting users to the database', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
