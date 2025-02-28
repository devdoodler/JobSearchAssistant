<?php

declare(strict_types=1);

namespace App\Shared\Domain;

abstract readonly class DomainEvent
{
    public function __construct(
        public int $aggregateId,
        public string $name,
        public int $number,
        public int $version,
        public int $occurredAt
    ) {
    }
}
