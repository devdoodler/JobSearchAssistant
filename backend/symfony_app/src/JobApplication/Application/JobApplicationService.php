<?php

namespace App\JobApplication\Application;

use App\JobApplication\Domain\ValueObject\JobApplicationId;
use App\Shared\Domain\Repository\EventStoreRepository;
use App\JobApplication\Domain\JobApplication;
use Ramsey\Uuid\Guid\Guid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


readonly class JobApplicationService
{
    public function __construct(
        private EventStoreRepository     $eventStoreRepository,
        private EventDispatcherInterface $eventDispatcher
    ) {}

    public function addJobApplication(
            $company,
            $position,
            $details
    ): JobApplication {
        $id = new JobApplicationId(Guid::uuid4());
        $jobApplication = new JobApplication($id);
        $jobApplication->add($id, $company, $position, $details);

        $this->persistEvents($jobApplication);

        return $jobApplication;
    }

    public function submitJobApplication(
        string $id,
            $submitDate,
            $comment
    ): JobApplication {
        $jobApplication = $this->reconstitute($id);

        $jobApplication->submit(new JobApplicationId($id), $submitDate, $comment);

        $this->persistEvents($jobApplication);

        return $jobApplication;
    }

    private function persistEvents(JobApplication $jobApplication): void
    {
        foreach ($jobApplication->pullEvents() as $event) {
            $this->eventStoreRepository->append($event);
            $this->eventDispatcher->dispatch($event);
        }
    }

    private function reconstitute(string $id): JobApplication
    {
        $events = $this->eventStoreRepository->getEventsForAggregate($id);
        return JobApplication::reconstitute(new JobApplicationId($id), ...$events);
    }
}
