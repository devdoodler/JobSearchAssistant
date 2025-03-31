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

    public function findSorted(): array
    {
        return $this->entityManager
            ->getRepository(JobApplicationReadModel::class)
            ->findBy([], ['company' => 'ASC']);
    }

    public function findSortedBySubmitDate(?int $limit = null): array
    {
        $queryBuilder = $this->entityManager
            ->getRepository(JobApplicationReadModel::class)
            ->createQueryBuilder('j')
            ->select('DISTINCT j.submitDate')
            ->orderBy('j.submitDate', 'DESC');

        if ($limit !== null) {
            $queryBuilder->setMaxResults($limit);
        }

        $distinctDates = $queryBuilder->getQuery()->getResult();

        $queryBuilder = $this->entityManager
            ->getRepository(JobApplicationReadModel::class)
            ->createQueryBuilder('j')
            ->where('j.submitDate IN (:dates)')
            ->setParameter('dates', array_map(function($date) {
                return $date['submitDate'];
            }, $distinctDates))
            ->orderBy('j.submitDate', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }

    public function findById(string $id): ?JobApplicationReadModel
    {
        return $this->entityManager
            ->getRepository(JobApplicationReadModel::class)
            ->find($id);
    }
}
