<?php

declare(strict_types=1);

namespace App\JobApplication\Domain;

use App\JobApplication\Domain\ValueObject\Comment;
use App\JobApplication\Domain\ValueObject\Company;
use App\JobApplication\Domain\ValueObject\Details;
use App\JobApplication\Domain\ValueObject\JobApplicationId;
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
        string $aggregateId,
        int $number,
        int $occurredAt,
        string $company,
        string $position,
        string $details,
        ?string $comment
    ) {
        $this->company = $company;
        $this->position = $position;
        $this->details = $details;

        parent::__construct($aggregateId, self::EVENT_NAME, $number, self::EVENT_VERSION, $occurredAt, $comment);
    }

    public static function occur(
        JobApplicationId $jobApplicationId,
        Version $aggregateVersion,
        Company $company,
        Position $position,
        Details $details,
        Comment $comment
    ): self {
        return new self(
            $jobApplicationId->toString(),
            $aggregateVersion->asNumber(),
            (new DateTimeImmutable())->getTimestamp(),
            $company->getName(),
            $position->getPosition(),
            $details->getDetails(),
            $comment->getComment()
        );
    }
}
