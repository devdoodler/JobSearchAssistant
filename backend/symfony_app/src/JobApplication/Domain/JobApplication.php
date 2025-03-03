<?php

namespace App\JobApplication\Domain;

use App\JobApplication\Domain\ValueObject\Comment;
use App\JobApplication\Domain\ValueObject\Company;
use App\JobApplication\Domain\ValueObject\DateTime;
use App\JobApplication\Domain\ValueObject\Details;
use App\JobApplication\Domain\ValueObject\JobApplicationId;
use App\JobApplication\Domain\ValueObject\Position;
use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\InvalidEventException;

class JobApplication extends AggregateRoot
{
    private JobApplicationId $id;
    private Company $company;
    private Position $position;
    private Details $details;
    private Comment $comment;
    private DateTime $submitDate;

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
    ): self {
        $this->record(
            JobApplicationAdded::occur(
                $id,
                $this->version()->next(),
                $company,
                $position,
                $details,
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
            JobApplicationSubmitted::class => $this->applyJobApplicationSubmitted($event),
            default => throw new InvalidEventException()
        };
    }

    private function applyJobApplicationAdded(JobApplicationAdded $event): void
    {
        $this->company = Company::create($event->company);
        $this->position = Position::create($event->position);
        $this->details = Details::create($event->details);
    }

    private function applyJobApplicationSubmitted(JobApplicationSubmitted $event): void
    {
        $this->submitDate = DateTime::fromString($event->submitDate);
        $this->comment = Comment::create($event->comment);
    }

    public function getId(): JobApplicationId
    {
        return $this->id;
    }
}
