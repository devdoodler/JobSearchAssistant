<?php

declare(strict_types=1);

namespace App\JobApplication\Domain;

use App\JobApplication\Domain\ValueObject\Company;
use App\JobApplication\Domain\ValueObject\Details;
use App\JobApplication\Domain\ValueObject\Position;
use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\Version;
use DateTimeImmutable;

final readonly class JobApplicationAdded extends DomainEvent
{
    public const int EVENT_VERSION = 1;

    public const string EVENT_NAME = 'job_application_added';

    public string $company;

    public string $position;

    public string $details;

    public function __construct(
        int $aggregateId,
        int $number,
        int $occurredAt,
        string $company,
        string $position,
        string $details
    ) {
        $this->company = $company;
        $this->position = $position;
        $this->details = $details;

        parent::__construct($aggregateId, self::EVENT_NAME, $number, self::EVENT_VERSION, $occurredAt);
    }

    public static function occur(
        int $jobApplicationId,
        Version $aggregateVersion,
        Company $company,
        Position $position,
        Details $details,
    ): self {
        return new self(
            $jobApplicationId,
            $aggregateVersion->asNumber(),
            (new DateTimeImmutable())->getTimestamp(),
            $company->getName(),
            $position->getPosition(),
            $details->getDetails()
        );
    }
}
