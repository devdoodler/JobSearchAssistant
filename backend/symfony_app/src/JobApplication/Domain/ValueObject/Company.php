<?php

declare(strict_types=1);

namespace App\JobApplication\Domain\ValueObject;

final readonly class Company
{
    private const int NAME_MIN_LENGTH = 2;
    private const int NAME_MAX_LENGTH = 50;

    public function __construct(private string $name)
    {
    }

    public static function create(string $name):self {
        $company = new self($name);
        $company->guard();

        return $company;
    }

    public function getName(): string
    {
        return $this->name;
    }

    private function guard(): void
    {
        $nameLength = mb_strlen($this->name);
        if ($nameLength < self::NAME_MIN_LENGTH || $nameLength > self::NAME_MAX_LENGTH) {
            throw new InvalidCompany();
        }

    }
}
