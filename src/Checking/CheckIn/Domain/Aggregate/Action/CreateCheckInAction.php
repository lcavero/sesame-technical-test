<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Domain\Aggregate\Action;

use App\Checking\CheckIn\Domain\Aggregate\CheckInCreatedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInDeletedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInEndDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInId;
use App\Checking\CheckIn\Domain\Aggregate\CheckInStartDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUpdatedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUserId;
use App\Checking\CheckIn\Domain\Exception\CheckInEndDateIsLowerThanStartDateException;

trait CreateCheckInAction
{
    public static function create(
        CheckInId $id,
        CheckInStartDate $startDate,
        CheckInEndDate $endDate,
        CheckInUserId $userId
    ): self
    {
        if (null !== $endDate->value && ($endDate->value < $startDate->value)) {
            throw CheckInEndDateIsLowerThanStartDateException::create('CheckIn end date can\'t be lower than start date');
        }

        return new self(
            $id->value,
            $startDate,
            $endDate,
            $userId,
            CheckInCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            CheckInUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            CheckInDeletedAt::fromNull()
        );
    }
}
