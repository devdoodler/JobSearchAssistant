<?php

namespace App\JobApplication\Interface\Http\Controller;

use App\JobApplication\Application\JobApplicationReadService;
use App\JobApplication\Application\JobApplicationService;
use App\JobApplication\Domain\ValueObject\Company;
use App\JobApplication\Domain\ValueObject\InterviewType;
use App\JobApplication\Domain\ValueObject\Position;
use App\JobApplication\Domain\ValueObject\Details;
use App\JobApplication\Domain\ValueObject\Comment;
use App\JobApplication\Domain\ValueObject\DateTime;
use App\JobApplication\Infrastructure\Persistence\Doctrine\JobApplicationReadModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JobApplicationController extends AbstractController
{

    public function __construct(
        private readonly JobApplicationService $jobApplicationService,
        private readonly JobApplicationReadService $jobApplicationReadService,
    ) {}

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
            $comment = $data['comment'] ? Comment::create($data['comment']) : Comment::create("");

            $jobApplication = $this->jobApplicationService->addJobApplication(
                $company,
                $position,
                $details,
                $comment
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

    #[Route('/job-application/reject', name: 'job_application_reject', methods: ['POST'])]
    public function reject(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['id'], $data['comment'])) {
            return new JsonResponse(['error' => 'Invalid input data'], JsonResponse::HTTP_BAD_REQUEST);
        }
        try {
            $comment = Comment::create($data['comment']);

            $jobApplication = $this->jobApplicationService->rejectJobApplication(
                $data['id'],
                $comment
            );

            return new JsonResponse(
                ['message' => 'Job application rejected successfully'],
                JsonResponse::HTTP_OK
            );

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/job-application/interview/schedule', name: 'job_application_interview_schedule', methods: ['POST'])]
    public function interviewSchedule(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['id'], $data['scheduleDate'], $data['interviewType'], $data['comment'])) {
            return new JsonResponse(['error' => 'Invalid input data'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $data['scheduleDate'] = str_replace('T', ' ', $data['scheduleDate']);
        try {
            $scheduleDate = DateTime::fromString($data['scheduleDate']);
            $comment = Comment::create($data['comment']);
            $interviewType = InterviewType::create($data['interviewType']);

            $jobApplication = $this->jobApplicationService->scheduleJobApplicationInterview(
                $data['id'],
                $scheduleDate,
                $interviewType,
                $comment
            );

            return new JsonResponse(
                ['message' => 'Job application interview scheduled successfully'],
                JsonResponse::HTTP_OK
            );

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/job-application/interview/held', name: 'job_application_interview_held', methods: ['POST'])]
    public function interviewHeld(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['id'], $data['interviewId'], $data['comment'])) {
            return new JsonResponse(['error' => 'Invalid input data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $comment = Comment::create($data['comment']);

            $jobApplication = $this->jobApplicationService->jobApplicationInterviewWasHeld(
                $data['id'],
                $data['interviewId'],
                $comment
            );

            return new JsonResponse(
                ['message' => 'Job application interview held on added successfully'],
                JsonResponse::HTTP_OK
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
            $jobApplications = $this->jobApplicationReadService->getJobApplicationsList();

            return new JsonResponse(
                ['jobApplications' => $jobApplications],
                JsonResponse::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/job-application/list/submit/total', name: 'job_application_list_submit_total', methods: ['GET'])]
    public function listBySubmitTotal(): JsonResponse
    {
        try {
            $jobApplicationsSubmitTotal = $this->jobApplicationReadService->getJobApplicationsListSubmitTotal();

            return new JsonResponse(
                ['jobApplicationsSubmitTotal' => $jobApplicationsSubmitTotal],
                JsonResponse::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/job-application/list/submit/{limit}', name: 'job_application_list_submit', methods: ['GET'])]
    public function listBySubmitDate(int $limit): JsonResponse
    {
        try {
            $jobApplications = $this->jobApplicationReadService->getJobApplicationsListBySubmitDate($limit);

            return new JsonResponse(
                ['jobApplications' => $jobApplications],
                JsonResponse::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/job-application/{id}', name: 'job_application_details', methods: ['GET'])]
    public function details(string $id): JsonResponse
    {
        try {
            $jobApplicationDetails = $this->jobApplicationReadService->getJobApplicationDetails($id);

            if (!$jobApplicationDetails) {
                return new JsonResponse(['error' => 'Job application not found'], JsonResponse::HTTP_NOT_FOUND);
            }

            return new JsonResponse($jobApplicationDetails, JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
