<?php

declare(strict_types=1);

namespace App\JobApplication\Domain\Events;

use App\JobApplication\Domain\ValueObject\Comment;
use App\JobApplication\Domain\ValueObject\DateTime;
use App\JobApplication\Domain\ValueObject\JobApplicationId;
use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\Version;
use DateTimeImmutable;

final readonly class JobApplicationSubmitted extends DomainEvent
{
    public const int EVENT_VERSION = 1;

    public const string EVENT_NAME = 'job_application_submitted';

    public string $submitDate;

    public function __construct(
        string $aggregateId,
        int $number,
        int $occurredAt,
        string $submitDate,
        ?string $comment,
    ) {
        $this->submitDate = $submitDate;

        parent::__construct($aggregateId, self::EVENT_NAME, $number, self::EVENT_VERSION, $occurredAt, $comment);
    }

    public static function occur(
        JobApplicationId $jobApplicationId,
        Version $aggregateVersion,
        DateTime $submitDate,
        Comment $comment,
    ): self {
        return new self(
            $jobApplicationId->toString(),
            $aggregateVersion->asNumber(),
            (new DateTimeImmutable())->getTimestamp(),
            $submitDate->toString(),
            $comment->getComment(),
        );
    }
}
