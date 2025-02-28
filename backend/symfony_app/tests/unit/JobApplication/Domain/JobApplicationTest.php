<?php

declare(strict_types=1);

use App\JobApplication\Domain\JobApplication;
use App\JobApplication\Domain\JobApplicationAdded;
use App\JobApplication\Domain\JobApplicationSubmitted;
use App\JobApplication\Domain\ValueObject\Comment;
use App\JobApplication\Domain\ValueObject\Company;
use App\JobApplication\Domain\ValueObject\DateTime;
use App\JobApplication\Domain\ValueObject\Details;
use App\JobApplication\Domain\ValueObject\Position;
use App\Shared\Domain\Version;
use PHPUnit\Framework\TestCase;

final class JobApplicationTest extends TestCase
{
    public function testAddWhenDataIsValidShouldAddJobApplication(): void
    {
        $id = 1;
        $company = new Company('companyA');
        $position = new Position('PHP Dev');
        $details = new Details('working shift: 12h');
        $expectedEvent = JobApplicationAdded::occur(
            $id,
            new Version(1),
            $company,
            $position,
            $details,
        );

        $SUT = new JobApplication(1);
        $SUT->add($id, $company, $position, $details);

        $events = $SUT->pullEvents();
        self::assertCount(1, $events);
        self::assertEquals($expectedEvent, $events[0]);
    }

    public function testSubmitWhenDataIsValidAndAddedShouldSubmitJobApplication(): void
    {
        $id = 1;
        $company = new Company('companyA');
        $position = new Position('PHP Dev');
        $details = new Details('working shift: 12h');
        $submitDate = DateTime::fromString('2025-02-02 02:02');
        $comment = new Comment('test comment');

        $expectedFirstEvent = JobApplicationAdded::occur(
            $id,
            new Version(1),
            $company,
            $position,
            $details,
        );

        $expectedSecondEvent = JobApplicationSubmitted::occur(
            $id,
            new Version(2),
            $submitDate,
            $comment
        );
        $SUT = new JobApplication(1);
        $SUT->add($id, $company, $position, $details);
        $SUT->submit($id, $submitDate, $comment);

        $events = $SUT->pullEvents();
        self::assertCount(2, $events);
        self::assertEquals($expectedFirstEvent, $events[0]);
        self::assertEquals($expectedSecondEvent, $events[1]);
    }
}
