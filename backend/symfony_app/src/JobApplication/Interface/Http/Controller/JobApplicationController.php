<?php

namespace App\JobApplication\Interface\Http\Controller;

use App\JobApplication\Application\JobApplicationService;
use App\JobApplication\Domain\ValueObject\Company;
use App\JobApplication\Domain\ValueObject\Position;
use App\JobApplication\Domain\ValueObject\Details;
use App\JobApplication\Domain\ValueObject\Comment;
use App\JobApplication\Domain\ValueObject\DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JobApplicationController extends AbstractController
{
    private JobApplicationService $jobApplicationService;

    public function __construct(JobApplicationService $jobApplicationService)
    {
        $this->jobApplicationService = $jobApplicationService;
    }

    #[Route('/job-application/add', name: 'job_application_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['company'], $data['position'], $data['details'])) {
            return new JsonResponse(['error' => 'Invalid input data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $company = Company::create($data['company']);
            $position = Position::create($data['position']);
            $details = Details::create($data['details']);

            $jobApplication = $this->jobApplicationService->addJobApplication(
                $company,
                $position,
                $details
            );

            return new JsonResponse(
                [
                    'message' => 'Job application added successfully',
                    'id' => $jobApplication->getId()->toString()
                ],
                JsonResponse::HTTP_CREATED
            );

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/job-application/submit', name: 'job_application_submit', methods: ['POST'])]
    public function submit(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['id'], $data['submitDate'], $data['comment'])) {
            return new JsonResponse(['error' => 'Invalid input data'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $data['submitDate'] = str_replace('T', ' ', $data['submitDate']);
        try {
            $submitDate = DateTime::fromString($data['submitDate']);
            $comment = Comment::create($data['comment']);

            $jobApplication = $this->jobApplicationService->submitJobApplication(
                $data['id'],
                $submitDate,
                $comment
            );

            return new JsonResponse(
                ['message' => 'Job application submitted successfully'],
                JsonResponse::HTTP_OK
            );

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/job-application/list', name: 'job_application_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        try {
            $contract = [
                [
                    "id" => "f2e2d9d8-0fe5-4d1c-a07d-3b622dff999a",
                    "company" => "Company A",
                    "eventName" => "job_application_submitted",
                    "submitDate" => "2024-02-03",
                ],
                [
                    "id" => "e1341355-6f66-4f31-9812-d1a1927191e4",
                    "company" => "Company B",
                    "eventName" => "job_application_added",
                    "submitDate" => null,
                ],
            ];

            //$jobApplications = $this->jobApplicationService->getJobApplicationsList();

            return new JsonResponse(
                ['jobApplications' => $contract],
                JsonResponse::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
