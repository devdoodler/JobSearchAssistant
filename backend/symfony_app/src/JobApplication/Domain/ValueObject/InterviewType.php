<?php

declare(strict_types=1);

namespace App\JobApplication\Domain\ValueObject;

use App\JobApplication\Domain\Enum\InterviewTypeEnum;

final readonly class InterviewType
{
    public function __construct(private InterviewTypeEnum $type)
    {
    }

    public static function create(string $type):self {
        $interviewType = new self(InterviewTypeEnum::from($type));
        $interviewType->guard();

        return $interviewType;
    }

    public function getType(): string
    {
        return $this->type->value;
    }

    private function guard(): void
    {
    }
}
