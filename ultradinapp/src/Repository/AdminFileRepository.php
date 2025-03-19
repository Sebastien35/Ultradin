<?php

namespace App\Repository;

use App\Entity\AdminFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdminFile>
 */
class AdminFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminFile::class);
    }

    public function create($name, $file_url, $date){
        $adminFile = new AdminFile();
        $adminFile->setName($name);
        $adminFile->setBlobName($file_url);
        $adminFile->setDateCreated($date);
        $adminFile->setExtension(pathinfo($file_url, PATHINFO_EXTENSION));
        $this->getEntityManager()->persist($adminFile);
        $this->getEntityManager()->flush();
    }
}
