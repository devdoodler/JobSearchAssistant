<?php

declare(strict_types=1);

namespace App\JobApplication\Domain\ValueObject;

final readonly class Position
{
    private const int POSITION_MIN_LENGTH = 2;
    private const int POSITION_MAX_LENGTH = 50;

    public function __construct(private string $position)
    {
    }

    public static function create(string $position):self {
        $company = new self($position);
        $company->guard();

        return $company;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    private function guard(): void
    {
        $positionLength = mb_strlen($this->position);
        if ($positionLength < self::POSITION_MIN_LENGTH || $positionLength > self::POSITION_MAX_LENGTH) {
            throw new InvalidPosition();
        }

    }
}
