<?php

namespace App\JobApplication\Application;

use App\JobApplication\Domain\Repository\JobApplicationReadModelRepositoryInterface;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineEventStoreRepository;
use App\Shared\Infrastructure\Persistence\Doctrine\EventEntity;

class JobApplicationReadService
{
    public function __construct(
        private readonly JobApplicationReadModelRepositoryInterface $jobApplicationReadModelRepository,
        private readonly DoctrineEventStoreRepository  $doctrineEventStoreRepository
    ) {}

    public function getJobApplicationsList(): array
    {
        $jobApplications = $this->jobApplicationReadModelRepository->findAll();

        return array_map(function ($jobApplication) {
            return [
                'id' => $jobApplication->getId(),
                'company' => $jobApplication->getCompany(),
                'submitDate' => $jobApplication->getSubmitDate(),
                'eventName' => $jobApplication->getEvent(),
                'comment' => $jobApplication->getComment(),
            ];
        }, $jobApplications);
    }


    public function getJobApplicationDetails(string $id): ?array
    {
        $jobApplication = $this->jobApplicationReadModelRepository->findById($id);

        if (!$jobApplication) {
            throw new \Exception('Job application not found.');
        }

        $events = $this->doctrineEventStoreRepository->getEvents($id);

        $jobApplicationData = [
            'id' => $jobApplication->getId(),
            'company' => $jobApplication->getCompany(),
            'position' => $jobApplication->getPosition(),
            'details' => $jobApplication->getDetails(),
            'submitDate' => $jobApplication->getSubmitDate(),
            'comment' => $jobApplication->getComment(),
            'status' => $jobApplication->getEvent(),
        ];

        $eventData = array_map(function (EventEntity $event) {
            return [
                'event_name' => $event->getEventName(),
                'version' => $event->getVersion(),
                'occurred_at' => date("Y-m-d H:i", $event->getOccurredAt()),
                'data' => $event->getData(),
                'comment' => $event->getComment(),
            ];
        }, $events);

        return [
            'job_application' => $jobApplicationData,
            'events' => $eventData,
        ];
    }
}
