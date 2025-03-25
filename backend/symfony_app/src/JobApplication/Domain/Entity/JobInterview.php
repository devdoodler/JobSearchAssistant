<?php

namespace App\JobApplication\Domain\Entity;

use App\JobApplication\Domain\ValueObject\DateTime;
use App\JobApplication\Domain\ValueObject\InterviewType;
use App\JobApplication\Domain\ValueObject\JobInterviewId;
use App\JobApplication\Domain\ValueObject\WasHeld;

class JobInterview
{
    private JobInterviewId $id;
    private InterviewType $type;
    private WasHeld $wasHeld;
    private DateTime $interviewDate;

    public function __construct(
        JobInterviewId $id,
    ) {
        $this->id = $id;
    }

    public function schedule(
        InterviewType $interviewType,
        DateTime $interviewDate,
    ): self {
        $this->interviewDate = $interviewDate;
        $this->type = $interviewType;
        $this->wasHeld = new WasHeld(false);

        return $this;
    }

    public function wasHeld(): self
    {
        $this->wasHeld = WasHeld::create(true);

        return $this;
    }

    public function getId(): JobInterviewId
    {
        return $this->id;
    }
}
