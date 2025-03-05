<?php

namespace App\JobApplication\Infrastructure\Listener;

use App\JobApplication\Domain\JobApplicationAdded;
use App\JobApplication\Domain\JobApplicationSubmitted;
use App\JobApplication\Infrastructure\Persistence\Doctrine\JobApplicationReadModel;
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
            JobApplicationSubmitted::class => 'onJobApplicationSubmitted',
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
}
