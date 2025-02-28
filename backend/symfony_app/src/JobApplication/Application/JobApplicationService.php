<?php

namespace App\JobApplication\Application;

use App\Shared\Domain\Repository\EventStoreRepository;
use App\JobApplication\Domain\JobApplication;

class JobApplicationService
{
    private EventStoreRepository $eventStoreRepository;

    public function __construct(EventStoreRepository $eventStoreRepository)
    {
        $this->eventStoreRepository = $eventStoreRepository;
    }

    public function addJobApplication(
        int $id,
            $company,
            $position,
            $details
    ): JobApplication {
        $jobApplication = new JobApplication($id);
        $jobApplication->add($id, $company, $position, $details);

        $this->persistEvents($jobApplication);

        return $jobApplication;
    }

    public function submitJobApplication(
        int $id,
            $submitDate,
            $comment
    ): JobApplication {
        $jobApplication = $this->reconstitute($id);

        $jobApplication->submit($id, $submitDate, $comment);

        $this->persistEvents($jobApplication);

        return $jobApplication;
    }

    private function persistEvents(JobApplication $jobApplication): void
    {
        foreach ($jobApplication->pullEvents() as $event) {
            $this->eventStoreRepository->append($event);
        }
    }

    private function reconstitute(int $id): JobApplication
    {
        $events = $this->eventStoreRepository->getEventsForAggregate($id);
        return JobApplication::reconstitute($id, ...$events);
    }
}
