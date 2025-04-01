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
        $sql = "
        SELECT DISTINCT DATE(j.submit_date) AS submitDate
        FROM job_applications j
        ORDER BY submitDate DESC
    ";

        if ($limit !== null) {
            $sql .= " LIMIT " . $limit;
        }

        $distinctDates = $this->entityManager->getConnection()->fetchAllAssociative($sql);

        $paramDates = array_map(function($date) {
            return $date['submitDate'];
        }, $distinctDates);

        $queryBuilder = $this->entityManager
            ->getRepository(JobApplicationReadModel::class)
            ->createQueryBuilder('j')
            ->where('j.submitDate BETWEEN :dateMin AND :dateMax')
            ->setParameter('dateMin', $paramDates[array_key_last($paramDates)] . ' 00:00')
            ->setParameter('dateMax', $paramDates[0] . ' 23:59')
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
