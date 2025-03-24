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

final readonly class JobInterviewScheduled extends DomainEvent
{
    public const int EVENT_VERSION = 1;

    public const string EVENT_NAME = 'job_interview_scheduled';

    public string $interviewId;

    public string $scheduledDate;

    public string $interviewType;

    public function __construct(
        string $aggregateId,
        int $number,
        int $occurredAt,
        string $interviewId,
        string $scheduledDate,
        string $interviewType,
        ?string $comment,
    ) {
        $this->interviewId = $interviewId;
        $this->scheduledDate = $scheduledDate;
        $this->interviewType = $interviewType;

        parent::__construct($aggregateId, self::EVENT_NAME, $number, self::EVENT_VERSION, $occurredAt, $comment);
    }

    public static function occur(
        JobApplicationId $jobApplicationId,
        Version $aggregateVersion,
        JobInterviewId $interviewId,
        DateTime $scheduledDate,
        InterviewType $interviewType,
        Comment $comment,
    ): self {
        return new self(
            $jobApplicationId->toString(),
            $aggregateVersion->asNumber(),
            (new DateTimeImmutable())->getTimestamp(),
            $interviewId->toString(),
            $scheduledDate->toString(),
            $interviewType->getType(),
            $comment->getComment(),
        );
    }
}
