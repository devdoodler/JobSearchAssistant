<?php

namespace App\JobApplication\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "job_interviews")]
class JobInterviewReadModel
{
    #[ORM\Id]
    #[ORM\Column(type: "string")]
    private string $id;

    #[ORM\Column(type: "string")]
    private string $interviewType;

    #[ORM\Column(type: "string")]
    private string $interviewDate;

    #[ORM\Column(type: "boolean")]
    private bool $wasHeld;

    // Foreign key relationship with JobApplicationReadModel (Many JobInterviews can belong to one JobApplication)
    #[ORM\ManyToOne(targetEntity: JobApplicationReadModel::class, inversedBy: "jobInterviews")]
    #[ORM\JoinColumn(name: "job_application_id", referencedColumnName: "id", nullable: false)]
    private JobApplicationReadModel $jobApplication;

    public function __construct(
        string $id,
        string $interviewType,
        string $interviewDate,
        bool $wasHeld,
        JobApplicationReadModel $jobApplication, // Ensure JobApplicationReadModel is passed to the constructor
    ) {
        $this->id = $id;
        $this->interviewType = $interviewType;
        $this->interviewDate = $interviewDate;
        $this->wasHeld = $wasHeld;
        $this->jobApplication = $jobApplication; // Set the foreign key reference
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getInterviewType(): string
    {
        return $this->interviewType;
    }

    public function getInterviewDate(): string
    {
        return $this->interviewDate;
    }

    public function getWasHeld(): bool
    {
        return $this->wasHeld;
    }

    public function setInterviewType(string $interviewType): void
    {
        $this->interviewType = $interviewType;
    }

    public function setInterviewDate(string $interviewDate): void
    {
        $this->interviewDate = $interviewDate;
    }

    public function setWasHeld(bool $wasHeld): void
    {
        $this->wasHeld = $wasHeld;
    }

    public function getJobApplication(): JobApplicationReadModel
    {
        return $this->jobApplication;
    }

    public function setJobApplication(JobApplicationReadModel $jobApplication): void
    {
        $this->jobApplication = $jobApplication;
    }
}
