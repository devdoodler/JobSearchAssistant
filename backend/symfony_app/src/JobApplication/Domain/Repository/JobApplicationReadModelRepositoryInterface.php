<?php

namespace App\JobApplication\Domain\Repository;

use App\JobApplication\Infrastructure\Persistence\Doctrine\JobApplicationReadModel;

interface JobApplicationReadModelRepositoryInterface
{
    public function findAll(): array;

    public function findSorted(): array;

    public function findSortedBySubmitDate(int $limit): array;

    public function findTotalBySubmitDate(): array;

    public function findTotalByEvent(): array;

    public function findById(string $id): ?JobApplicationReadModel;
}
