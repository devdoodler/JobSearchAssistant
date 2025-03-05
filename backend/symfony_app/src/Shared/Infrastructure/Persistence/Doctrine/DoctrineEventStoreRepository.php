<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\JobApplication\Domain\JobApplicationAdded;
use App\JobApplication\Domain\JobApplicationSubmitted;
use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\Repository\EventStoreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;

class DoctrineEventStoreRepository implements EventStoreRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function append(DomainEvent $event): void
    {
        $eventEntity = new EventEntity(
            $event->aggregateId,
            $event::EVENT_NAME,
            $event->version,
            $event->occurredAt,
            json_encode($event, JSON_UNESCAPED_UNICODE),
            $event->comment
        );

        $this->entityManager->persist($eventEntity);
        $this->entityManager->flush();
    }

    public function getEventsForAggregate(string $aggregateId): array
    {
        $eventEntities = $this->entityManager->getRepository(EventEntity::class)->findBy(
            ['aggregateId' => $aggregateId],
            ['version' => 'ASC']
        );

        $events = [];
        foreach ($eventEntities as $eventEntity) {
            $eventData = json_decode($eventEntity->getData(), true);
            switch ($eventEntity->getEventName()) {
                case JobApplicationAdded::EVENT_NAME:
                    $events[] = new JobApplicationAdded(
                        $eventEntity->getAggregateId(),
                        $eventEntity->getVersion(),
                        $eventEntity->getOccurredAt(),
                        $eventData['company'],
                        $eventData['position'],
                        $eventData['details'],
                        $eventEntity->getComment()
                    );
                    break;

                case JobApplicationSubmitted::EVENT_NAME:
                    $events[] = new JobApplicationSubmitted(
                        $eventEntity->getAggregateId(),
                        $eventEntity->getVersion(),
                        $eventEntity->getOccurredAt(),
                        $eventData['submitDate'],
                        $eventEntity->getComment()
                    );
                    break;

                default:
                    throw new \Exception('Unknown event type');
            }
        }

        return $events;
    }
}
