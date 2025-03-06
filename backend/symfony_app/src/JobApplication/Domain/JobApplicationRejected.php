<?php

declare(strict_types=1);

namespace App\JobApplication\Domain;

use App\JobApplication\Domain\ValueObject\Comment;
use App\JobApplication\Domain\ValueObject\JobApplicationId;
use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\Version;
use DateTimeImmutable;

final readonly class JobApplicationRejected extends DomainEvent
{
    public const int EVENT_VERSION = 1;

    public const string EVENT_NAME = 'job_application_rejected';


    public function __construct(
        string $aggregateId,
        int $number,
        int $occurredAt,
        ?string $comment,
    ) {
        parent::__construct($aggregateId, self::EVENT_NAME, $number, self::EVENT_VERSION, $occurredAt, $comment);
    }

    public static function occur(
        JobApplicationId $jobApplicationId,
        Version $aggregateVersion,
        Comment $comment,
    ): self {
        return new self(
            $jobApplicationId->toString(),
            $aggregateVersion->asNumber(),
            (new DateTimeImmutable())->getTimestamp(),
            $comment->getComment(),
        );
    }
}
