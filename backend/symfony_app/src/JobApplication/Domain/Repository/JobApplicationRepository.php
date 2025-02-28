<?php

declare(strict_types=1);

namespace App\JobApplication\Domain\Repository;

use App\JobApplication\Domain\JobApplication;

interface JobApplicationRepository
{
    public function save(JobApplication $jobApplication): void;

//    /**
//     * @throws JobApplicationNotFound
//     */
    public function get(int $id): JobApplication;
}
