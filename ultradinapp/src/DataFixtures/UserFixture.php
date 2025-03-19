<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Psr\Log\LoggerInterface;
use Faker\Factory;


class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private LoggerInterface $logger;
    private \Faker\Generator $faker;

    public function __construct(UserPasswordHasherInterface $passwordHasher, LoggerInterface $logger)
    {
        $this->passwordHasher = $passwordHasher;
        $this->logger = $logger;
        
    }

    public function load(ObjectManager $manager): void
    {   

        $faker = Factory::create();
        $this->faker = $faker;


        $rowCount = (int) $manager->getRepository(User::class)->createQueryBuilder('u')
            ->select('COUNT(u.id_user)')
            ->getQuery()
            ->getSingleScalarResult();

        if ($rowCount > 0) {
            $this->logger->info('Users already exist in the database. No need to create new users.');
            return;
        }

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

        for($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPhone(rand(0000000000, 9999999999));
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $user->setCreatedAt($faker->dateTimeThisYear());
            $manager->persist($user);
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
