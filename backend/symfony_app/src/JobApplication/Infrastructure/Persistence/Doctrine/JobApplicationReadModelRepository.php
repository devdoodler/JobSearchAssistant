<?php

namespace App\JobApplication\Infrastructure\Persistence\Doctrine;

use App\JobApplication\Domain\Repository\JobApplicationReadModelRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class JobApplicationReadModelRepository implements JobApplicationReadModelRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAll(): array
    {
        return $this->entityManager
            ->getRepository(JobApplicationReadModel::class)
            ->findAll();
    }
}
