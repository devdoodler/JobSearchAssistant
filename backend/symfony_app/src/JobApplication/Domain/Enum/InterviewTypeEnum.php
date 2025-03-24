<?php

declare(strict_types=1);

namespace App\JobApplication\Domain\Enum;

enum InterviewTypeEnum: string
{
    case InPerson = 'In-person';
    case Phone = 'Phone';
    case Video = 'Video';
    case Other = 'Other';
}
