<?php

namespace App\JobApplication\Application;

use App\JobApplication\Domain\Repository\JobApplicationReadModelRepositoryInterface;

class JobApplicationReadService
{
    public function __construct(
        private readonly JobApplicationReadModelRepositoryInterface $jobApplicationReadModelRepository
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
            ];
        }, $jobApplications);
    }
}
