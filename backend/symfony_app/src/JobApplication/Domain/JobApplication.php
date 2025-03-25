<?php

namespace App\JobApplication\Domain;

use App\JobApplication\Domain\Entity\JobInterview;
use App\JobApplication\Domain\Events\JobApplicationAdded;
use App\JobApplication\Domain\Events\JobApplicationRejected;
use App\JobApplication\Domain\Events\JobApplicationSubmitted;
use App\JobApplication\Domain\Events\JobInterviewScheduled;
use App\JobApplication\Domain\Events\JobInterviewWasHeld;
use App\JobApplication\Domain\ValueObject\Comment;
use App\JobApplication\Domain\ValueObject\Company;
use App\JobApplication\Domain\ValueObject\DateTime;
use App\JobApplication\Domain\ValueObject\Details;
use App\JobApplication\Domain\ValueObject\InterviewType;
use App\JobApplication\Domain\ValueObject\JobApplicationId;
use App\JobApplication\Domain\ValueObject\JobInterviewId;
use App\JobApplication\Domain\ValueObject\Position;
use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\InvalidEventException;
use Ramsey\Uuid\Guid\Guid;

class JobApplication extends AggregateRoot
{
    private JobApplicationId $id;
    private Company $company;
    private Position $position;
    private Details $details;
    private Comment $comment;
    private DateTime $submitDate;

    /**
     * JobInterview[] $interviews
     **/
    private array $interviews = [];

    public function __construct(
        JobApplicationId $id,
    ) {
        $this->id = $id;

        parent::__construct();
    }

    public function add(
        JobApplicationId $id,
        Company $company,
        Position $position,
        Details $details,
        Comment $comment
    ): self {
        $this->record(
            JobApplicationAdded::occur(
                $id,
                $this->version()->next(),
                $company,
                $position,
                $details,
                $comment
            )
        );

        return $this;
    }

    public function interviewSchedule(
        JobApplicationId $id,
        DateTime $scheduleDate,
        InterviewType $interviewType,
        Comment $comment,
    ): self {
        $interviewId = new JobInterviewId(Guid::uuid4());
        $interview = new JobInterview($interviewId);
        $interview->schedule(
            $interviewType,
            $scheduleDate
        );
        $this->interviews[$interviewId->toString()] = $interview;

        $this->record(
            JobInterviewScheduled::occur(
                $id,
                $this->version()->next(),
                $interviewId,
                $scheduleDate,
                $interviewType,
                $comment,
            )
        );

        return $this;
    }

    public function interviewWasHeld(
        JobApplicationId $id,
        JobInterviewId $interviewId,
        Comment $comment,
    ): self {
        $this->interviews[$interviewId->toString()]->wasHeld();

        $this->record(
            JobInterviewWasHeld::occur(
                $id,
                $this->version()->next(),
                $interviewId,
                $comment,
            )
        );

        return $this;
    }

    public function reject(
        JobApplicationId $id,
        Comment $comment,
    ): self {
        $this->record(
            JobApplicationRejected::occur(
                $id,
                $this->version()->next(),
                $comment,
            )
        );

        return $this;
    }

    public function submit(
        JobApplicationId $id,
        DateTime $submitDate,
        Comment $comment,
    ): self {
        $this->record(
            JobApplicationSubmitted::occur(
                $id,
                $this->version()->next(),
                $submitDate,
                $comment,
            )
        );

        return $this;
    }

    protected function apply(DomainEvent $event): void
    {
        match ($event::class) {
            JobApplicationAdded::class => $this->applyJobApplicationAdded($event),
            JobApplicationRejected::class => $this->applyJobApplicationRejected($event),
            JobApplicationSubmitted::class => $this->applyJobApplicationSubmitted($event),
            JobInterviewScheduled::class => $this->applyJobInterviewScheduled($event),
            JobInterviewWasHeld::class => $this->applyJobInterviewWasHeld($event),
            default => throw new InvalidEventException()
        };
    }

    private function applyJobApplicationAdded(JobApplicationAdded $event): void
    {
        $this->company = Company::create($event->company);
        $this->position = Position::create($event->position);
        $this->details = Details::create($event->details);
        $this->comment = Comment::create($event->comment ?? '');
    }

    private function applyJobApplicationRejected(JobApplicationRejected $event): void
    {
        $this->comment = Comment::create($event->comment);
    }

    private function applyJobApplicationSubmitted(JobApplicationSubmitted $event): void
    {
        $this->submitDate = DateTime::fromString($event->submitDate);
        $this->comment = Comment::create($event->comment ?? '');
    }

    private function applyJobInterviewScheduled(JobInterviewScheduled $event): void
    {
        $interview = new JobInterview(new JobInterviewId($event->interviewId));
        $interview->schedule(
            InterviewType::create($event->interviewType),
            DateTime::fromString($event->scheduledDate)
        );

        $this->interviews[$interview->getId()->toString()] = $interview;
        $this->comment = Comment::create($event->comment ?? '');
    }

    private function applyJobInterviewWasHeld(JobInterviewWasHeld $event): void
    {
        $interview = new JobInterview(new JobInterviewId($event->interviewId));
        $interview->wasHeld();

        $this->interviews[] = $interview;
        $this->comment = Comment::create($event->comment ?? '');
    }

    public function getId(): JobApplicationId
    {
        return $this->id;
    }
}
