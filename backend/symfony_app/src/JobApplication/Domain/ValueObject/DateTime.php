<?php

declare(strict_types=1);

namespace App\JobApplication\Domain\ValueObject;

use DateTimeImmutable;
use App\JobApplication\Domain\ValueObject\InvalidDate;

final readonly class DateTime
{
    private function __construct(
        private DateTimeImmutable $date
    ) {
    }

    public static function fromString(string $date): self
    {
        $date = DateTimeImmutable::createFromFormat('!Y-m-d H:i', $date);

        if ($date === false) {
            throw new InvalidDate();
        }

        return new self($date);
    }

    public function toString(): string
    {
        return $this->date->format('Y-m-d H:i');
    }

    public function toStringDate(): string
    {
        return $this->date->format('Y-m-d');
    }

    public function equalsDate(self $otherDate): bool
    {
        return $this->date->format('Y-m-d') === $otherDate->date->format('Y-m-d');
    }

    public function equals(self $otherDate): bool
    {
        return $this->date->format('Y-m-d H:i') === $otherDate->date->format('Y-m-d H:i');
    }
}
