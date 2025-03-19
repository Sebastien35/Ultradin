<?php

namespace App\Repository;

use App\Entity\UsersVerifications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\CommonController;

use App\Entity\User;

/**
 * @extends ServiceEntityRepository<UsersVerifications>
 */
class UsersVerificationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersVerifications::class);
    }

    public function getUserVerifications(User $user, $typeVerification = null): ?UsersVerifications
    {
        $qb = $this->createQueryBuilder('uv')
            ->where('uv.user_id = :user')
            ->setParameter('user', $user);

        if ($typeVerification) {
            $qb->andWhere('uv.type_verification = :type')
                ->setParameter('type', $typeVerification);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function createEmailVerification(User $user): UsersVerifications
    {
        // Vérifie s'il y a déjà une vérification d'email existante pour cet utilisateur
        $existingVerification = $this->getUserVerifications($user, 'email');
        
        if ($existingVerification) {
            // Si une vérification existe déjà, on la supprime
            $this->getEntityManager()->remove($existingVerification);
            $this->getEntityManager()->flush();
        }

        // Crée un nouveau code de vérification
        $code = rand(100000, 999999);
        
        // Crée une nouvelle instance de UsersVerifications
        $verification = new UsersVerifications();
        $verification->setUserId($user);
        $verification->setCreatedAt(new \DateTimeImmutable());
        $verification->setCodeVerification($code);
        $verification->setTypeVerification('email');
        $verification->setVerified(false);

        // Persiste la nouvelle vérification dans la base de données
        $this->getEntityManager()->persist($verification);
        $this->getEntityManager()->flush();

        // Envoie l'email de vérification
        CommonController::sendEmailVerificationEmail($user, $code);

        return $verification;
    }
}
