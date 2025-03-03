<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repository;

use App\Shared\Domain\DomainEvent;

interface EventStoreRepository
{
    public function append(DomainEvent $event): void;

    /**
     * Get all events for a given aggregate ID
     *
     * @param int $aggregateId
     * @return DomainEvent[]
     */
    public function getEventsForAggregate(string $aggregateId): array;
}
