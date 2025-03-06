<?php

declare(strict_types=1);

use App\JobApplication\Domain\JobApplicationAdded;
use App\JobApplication\Domain\ValueObject\Comment;
use App\JobApplication\Domain\ValueObject\Company;
use App\JobApplication\Domain\ValueObject\Details;
use App\JobApplication\Domain\ValueObject\JobApplicationId;
use App\JobApplication\Domain\ValueObject\Position;
use App\Shared\Domain\Version;
use PHPUnit\Framework\TestCase;

final class JobApplicationAddedTest extends TestCase
{
    public function testOccurGivenDataIsValidShouldAddEvent(): void
    {
        $id =   new JobApplicationId('6dd3ac36-f126-4a4d-b7bf-11a10f3837c2');
        $version = Version::zero();
        $company = new Company('companyA');
        $position = new Position('PHP Dev');
        $details = new Details('working shift: 12h');
        $comment = new Comment('comment');

        $SUT = JobApplicationAdded::occur($id, $version, $company, $position, $details, $comment);
        self::assertSame($id->toString(), $SUT->aggregateId);
        self::assertSame($version->asNumber(), $SUT->number);
        self::assertSame($company->getName(), $SUT->company);
        self::assertSame($position->getPosition(), $SUT->position);
        self::assertSame($details->getDetails(), $SUT->details);
        self::assertSame($comment->getComment(), $SUT->comment);

        self::assertSame(JobApplicationAdded::EVENT_NAME, $SUT->name);
        self::assertSame(JobApplicationAdded::EVENT_VERSION, $SUT->version);
    }
}
