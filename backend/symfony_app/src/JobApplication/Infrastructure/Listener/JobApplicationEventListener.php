<?php

namespace App\JobApplication\Infrastructure\Listener;

use App\JobApplication\Domain\Events\JobApplicationAdded;
use App\JobApplication\Domain\Events\JobApplicationRejected;
use App\JobApplication\Domain\Events\JobApplicationSubmitted;
use App\JobApplication\Domain\Events\JobInterviewScheduled;
use App\JobApplication\Domain\Events\JobInterviewWasHeld;
use App\JobApplication\Infrastructure\Persistence\Doctrine\JobApplicationReadModel;
use App\JobApplication\Infrastructure\Persistence\Doctrine\JobInterviewReadModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JobApplicationEventListener implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            JobApplicationAdded::class => 'onJobApplicationAdded',
            JobApplicationRejected::class => 'onJobApplicationRejected',
            JobApplicationSubmitted::class => 'onJobApplicationSubmitted',
            JobInterviewScheduled::class => 'onJobInterviewScheduled',
            JobInterviewWasHeld::class => 'onJobInterviewWasHeld',
        ];
    }

    public function onJobApplicationAdded(JobApplicationAdded $event): void
    {
        $readModel = new JobApplicationReadModel(
            $event->aggregateId,
            $event->company,
            $event->version,
            JobApplicationAdded::EVENT_NAME,
            $event->position,
            $event->details,
            $event->comment
        );

        $this->entityManager->persist($readModel);
        $this->entityManager->flush();
    }

    public function onJobApplicationSubmitted(JobApplicationSubmitted $event): void
    {
        /** @var JobApplicationReadModel $readModel */
        $readModel = $this->entityManager->getRepository(JobApplicationReadModel::class)->find($event->aggregateId);
        if ($readModel) {
            $readModel->setSubmitDate($event->submitDate);
            $readModel->setComment($event->comment);
            $readModel->setVersion($event->version);
            $readModel->setEvent(JobApplicationSubmitted::EVENT_NAME);
            $this->entityManager->flush();
        }
    }

    public function onJobApplicationRejected(JobApplicationRejected $event): void
    {
        /** @var JobApplicationReadModel $readModel */
        $readModel = $this->entityManager->getRepository(JobApplicationReadModel::class)->find($event->aggregateId);
        if ($readModel) {
            $readModel->setComment($event->comment);
            $readModel->setVersion($event->version);
            $readModel->setEvent(JobApplicationRejected::EVENT_NAME);
            $this->entityManager->flush();
        }
    }

    public function onJobInterviewScheduled(JobInterviewScheduled $event): void
    {
        /** @var JobApplicationReadModel $readModel */
        $readModel = $this->entityManager->getRepository(JobApplicationReadModel::class)->find($event->aggregateId);
        if ($readModel) {
            $readModel->setComment($event->comment);
            $readModel->setEvent(JobInterviewScheduled::EVENT_NAME);
            $this->entityManager->flush();
        }

        $JobInterviewReadModel = new JobInterviewReadModel(
            $event->interviewId,
            $event->interviewType,
            $event->scheduledDate,
            false,
            $readModel
        );
        $this->entityManager->persist($JobInterviewReadModel);
        $this->entityManager->flush();
    }

    public function onJobInterviewWasHeld(JobInterviewWasHeld $event): void
    {
        /** @var JobApplicationReadModel $readModel */
        $readModel = $this->entityManager->getRepository(JobApplicationReadModel::class)->find($event->aggregateId);
        if ($readModel) {
            $readModel->setComment($event->comment);
            $readModel->setEvent(JobInterviewWasHeld::EVENT_NAME);
            $this->entityManager->flush();
        }

        /** @var JobInterviewReadModel $jobInterviewReadModel */
        $jobInterviewReadModel = $this->entityManager->getRepository(JobInterviewReadModel::class)
            ->find($event->interviewId);
        if ($jobInterviewReadModel) {
            $jobInterviewReadModel->setWasHeld(true);
            $this->entityManager->flush();
        }
    }


}
