<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Domain\Aggregate;

use App\Checking\CheckIn\Domain\Aggregate\Action\CreateCheckInAction;
use App\Checking\CheckIn\Domain\Aggregate\Action\DeleteCheckInAction;
use App\Checking\CheckIn\Domain\Aggregate\Action\UpdateCheckInAction;

class CheckIn
{
    use CreateCheckInAction;
    use UpdateCheckInAction;
    use DeleteCheckInAction;

    private function __construct(
        private string $id,
        private CheckInStartDate $startDate,
        private CheckInEndDate $endDate,
        private CheckInUserId $userId,
        private CheckInCreatedAt $createdAt,
        private CheckInUpdatedAt $updatedAt,
        private CheckInDeletedAt $deletedAt
    ) {
    }

    public static function fromValues(
        CheckInId $id,
        CheckInStartDate $startDate,
        CheckInEndDate $endDate,
        CheckInUserId $userId,
        CheckInCreatedAt $createdAt,
        CheckInUpdatedAt $updatedAt,
        CheckInDeletedAt $deletedAt
    ): self
    {
        return new self(
            $id->value,
            $startDate,
            $endDate,
            $userId,
            $createdAt,
            $updatedAt,
            $deletedAt
        );
    }

    public function id(): CheckInId
    {
        return CheckInId::fromString($this->id);
    }

    public function createdAt(): CheckInCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): CheckInUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): CheckInDeletedAt
    {
        return $this->deletedAt;
    }

    public function startDate(): CheckInStartDate
    {
        return $this->startDate;
    }

    public function endDate(): CheckInEndDate
    {
        return $this->endDate;
    }

    public function userId(): CheckInUserId
    {
        return $this->userId;
    }
}
