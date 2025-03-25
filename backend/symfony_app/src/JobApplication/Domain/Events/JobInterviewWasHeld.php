<?php

declare(strict_types=1);

namespace App\JobApplication\Domain\Events;

use App\JobApplication\Domain\ValueObject\Comment;
use App\JobApplication\Domain\ValueObject\DateTime;
use App\JobApplication\Domain\ValueObject\InterviewType;
use App\JobApplication\Domain\ValueObject\JobApplicationId;
use App\JobApplication\Domain\ValueObject\JobInterviewId;
use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\Version;
use DateTimeImmutable;

final readonly class JobInterviewWasHeld extends DomainEvent
{
    public const int EVENT_VERSION = 1;

    public const string EVENT_NAME = 'job_interview_was_held';

    public string $interviewId;

    public function __construct(
        string $aggregateId,
        int $number,
        int $occurredAt,
        string $interviewId,
        ?string $comment,
    ) {
        $this->interviewId = $interviewId;

        parent::__construct($aggregateId, self::EVENT_NAME, $number, self::EVENT_VERSION, $occurredAt, $comment);
    }

    public static function occur(
        JobApplicationId $jobApplicationId,
        Version $aggregateVersion,
        JobInterviewId $interviewId,
        Comment $comment,
    ): self {
        return new self(
            $jobApplicationId->toString(),
            $aggregateVersion->asNumber(),
            (new DateTimeImmutable())->getTimestamp(),
            $interviewId->toString(),
            $comment->getComment(),
        );
    }
}
