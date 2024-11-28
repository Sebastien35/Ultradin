<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use App\Entity\UserAddress;
use App\Entity\CountryIso3;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function updateUser(User $user, $data){
        $user->setEmail($data['email'] ?? $user->getEmail());
        $user->setPhone($data['phone'] ?? $user->getPhone());
        if(isset($data['address'])){
            $address = $user->getAddress();
            if(!$address){
                $address = new UserAddress();
                $address->setUser($user);
            }
            $address->setCity($data['address']['city'] ?? $address->getCity());
            $address->setZip($data['address']['zip'] ?? $address->getZip());
            if (isset($data['address']['country'])) {
                $countryIso3Repository = $this->getEntityManager()->getRepository(CountryIso3::class);
                $country = $countryIso3Repository->findOneBy(['iso3' => $data['address']['country']]);
    
                if (!$country) {
                    throw new \InvalidArgumentException("Country with ISO3 '{$data['address']['country']}' not found.");
                }
    
                $address->setCountry($country);
            }
        }
    }

    public function getUserById(int $id): array
    {
        $user = $this->find($id);
        $user_f = [
            "id" => $user->getId(),
            "address" => $user->getAddress(),
            "email" => $user->getEmail(),
            "phone" => $user->getPhone(),
            "roles" => $user->getRoles(),
        ];
        return $user_f;
    }

    public function createAdminUser($data){
        if(!isset($data['email']) || !isset($data['phone']) || !isset($data['password'])){
            throw new \InvalidArgumentException('Missing required fields');
        }
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPhone($data['phone']);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
        $user->setCreatedAt(new \DateTime());
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        return $user;
    }
}
