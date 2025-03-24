<?php

namespace App\JobApplication\Infrastructure\Persistence\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "job_applications")]
class JobApplicationReadModel
{
    #[ORM\Id]
    #[ORM\Column(type: "string")]
    private string $id;

    #[ORM\Column(type: "string")]
    private string $company;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $position = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $details = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $submitDate = null;

    #[ORM\Column(type: "string", length: 1500, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: "integer")]
    private int $version;

    #[ORM\Column(type: "string")]
    private string $event;

    // One-to-many relationship with JobInterviewReadModel
    #[ORM\OneToMany(targetEntity: JobInterviewReadModel::class, mappedBy: "jobApplication")]
    private $jobInterviews;

    public function __construct(
        string $id,
        string $company,
        int $version,
        string $event,
        ?string $position = null,
        ?string $details = null,
        ?string $comment = null,
        ?string $submitDate = null,
    ) {
        $this->id = $id;
        $this->company = $company;
        $this->position = $position;
        $this->details = $details;
        $this->submitDate = $submitDate;
        $this->comment = $comment;
        $this->version = $version;
        $this->event = $event;
        $this->jobInterviews = new ArrayCollection();
    }

    // Getters and setters
    public function getId(): string
    {
        return $this->id;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function getSubmitDate(): ?string
    {
        return $this->submitDate;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function setSubmitDate(?string $submitDate): void
    {
        $this->submitDate = $submitDate;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function setVersion(int $version): void
    {
        $this->version = $version;
    }

    public function setEvent(string $event): void
    {
        $this->event = $event;
    }

    public function getJobInterviews(): ArrayCollection
    {
        return $this->jobInterviews;
    }

    public function addJobInterview(JobInterviewReadModel $jobInterview): void
    {
        if (!$this->jobInterviews->contains($jobInterview)) {
            $this->jobInterviews[] = $jobInterview;
            $jobInterview->setJobApplication($this); // Set the foreign key in JobInterviewReadModel
        }
    }

    public function removeJobInterview(JobInterviewReadModel $jobInterview): void
    {
        if ($this->jobInterviews->contains($jobInterview)) {
            $this->jobInterviews->removeElement($jobInterview);
            $jobInterview->setJobApplication(null); // Remove the foreign key in JobInterviewReadModel
        }
    }
}
