<?php

declare(strict_types=1);

use App\JobApplication\Domain\Events\JobApplicationAdded;
use App\JobApplication\Domain\Events\JobApplicationSubmitted;
use App\JobApplication\Domain\JobApplication;
use App\JobApplication\Domain\ValueObject\Comment;
use App\JobApplication\Domain\ValueObject\Company;
use App\JobApplication\Domain\ValueObject\DateTime;
use App\JobApplication\Domain\ValueObject\Details;
use App\JobApplication\Domain\ValueObject\JobApplicationId;
use App\JobApplication\Domain\ValueObject\Position;
use App\Shared\Domain\Version;
use PHPUnit\Framework\TestCase;

final class JobApplicationTest extends TestCase
{
    public function testAddWhenDataIsValidShouldAddJobApplication(): void
    {
        $id = new JobApplicationId('bef5692b-c184-4f92-b6bb-ab901dd674b4');
        $company = new Company('companyA');
        $position = new Position('PHP Dev');
        $details = new Details('working shift: 12h');
        $comment = new Comment('comment');
        $expectedEvent = JobApplicationAdded::occur(
            $id,
            new Version(1),
            $company,
            $position,
            $details,
            $comment
        );

        $SUT = new JobApplication($id);
        $SUT->add($id, $company, $position, $details, $comment);

        $events = $SUT->pullEvents();
        self::assertCount(1, $events);
        self::assertEquals($expectedEvent, $events[0]);
    }

    public function testSubmitWhenDataIsValidAndAddedShouldSubmitJobApplication(): void
    {
        $id =  new JobApplicationId('1f6aa17d-2a8c-4bfc-9fb5-5b5bd00349a2');
        $company = new Company('companyA');
        $position = new Position('PHP Dev');
        $details = new Details('working shift: 12h');
        $submitDate = DateTime::fromString('2025-02-02 02:02');
        $comment = new Comment('test comment');
        $commentSubmit = new Comment('test comment changed');

        $expectedFirstEvent = JobApplicationAdded::occur(
            $id,
            new Version(1),
            $company,
            $position,
            $details,
            $comment
        );

        $expectedSecondEvent = JobApplicationSubmitted::occur(
            $id,
            new Version(2),
            $submitDate,
            $commentSubmit
        );
        $SUT = new JobApplication($id);
        $SUT->add($id, $company, $position, $details, $comment);
        $SUT->submit($id, $submitDate, $commentSubmit);

        $events = $SUT->pullEvents();
        self::assertCount(2, $events);
        self::assertEquals($expectedFirstEvent, $events[0]);
        self::assertEquals($expectedSecondEvent, $events[1]);
    }
}
