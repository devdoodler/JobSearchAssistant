<?php

declare(strict_types=1);

namespace App\JobApplication\Domain\ValueObject;

final readonly class WasHeld
{
    public function __construct(private bool $wasHeld)
    {
    }

    public static function create(bool $wasHeld):self {
        $wasHeld = new self($wasHeld);
        $wasHeld->guard();

        return $wasHeld;
    }

    public function getWasHeld(): bool
    {
        return $this->wasHeld;
    }

    private function guard(): void
    {
        if (!is_bool($this->wasHeld)) {
            throw new InvalidWasHeld();
        }

    }
}
