<?php

declare(strict_types=1);

namespace App\JobApplication\Domain\ValueObject;

final readonly class Details
{
    private const int DETAILS_MAX_LENGTH = 1500;

    public function __construct(private string $details)
    {
    }

    public static function create(string $details):self {
        $company = new self($details);
        $company->guard();

        return $company;
    }

    public function getDetails(): string
    {
        return $this->details;
    }

    private function guard(): void
    {
        $detailsLength = mb_strlen($this->details);
        if ($detailsLength > self::DETAILS_MAX_LENGTH) {
            throw new InvalidDetails();
        }

    }
}
