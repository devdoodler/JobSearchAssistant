<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "domain_events")]
class EventEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string")]
    private string $aggregateId;

    #[ORM\Column(type: "string")]
    private string $eventName;

    #[ORM\Column(type: "integer")]
    private int $version;

    #[ORM\Column(type: "integer")]
    private int $occurredAt;

    #[ORM\Column(type: "json")]
    private string $data;

    public function __construct(
        string $aggregateId,
        string $eventName,
        int $version,
        int $occurredAt,
        string $data
    ) {
        $this->aggregateId = $aggregateId;
        $this->eventName = $eventName;
        $this->version = $version;
        $this->occurredAt = $occurredAt;
        $this->data = $data;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAggregateId():string
    {
        return $this->aggregateId;
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getOccurredAt(): int
    {
        return $this->occurredAt;
    }

    public function getData(): string
    {
        return $this->data;
    }
}


