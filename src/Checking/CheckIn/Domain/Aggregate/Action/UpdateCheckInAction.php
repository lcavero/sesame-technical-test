<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Domain\Aggregate\Action;

use App\Checking\CheckIn\Domain\Aggregate\CheckInEndDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInStartDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUpdatedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUserId;
use App\Checking\CheckIn\Domain\Exception\CheckInEndDateIsLowerThanStartDateException;

trait UpdateCheckInAction
{
    public function update(
        CheckInStartDate $startDate,
        CheckInEndDate $endDate,
        CheckInUserId $userId
    ): void
    {
        if (null !== $endDate->value && ($endDate->value < $startDate->value)) {
            throw CheckInEndDateIsLowerThanStartDateException::create('CheckIn end date can\'t be lower than start date');
        }

        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->userId = $userId;
        $this->updatedAt = CheckInUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable());
    }
}
