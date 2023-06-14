<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Domain\Aggregate\Action;

use App\Checking\CheckIn\Domain\Aggregate\CheckInDeletedAt;

trait DeleteCheckInAction
{
    public function delete(): void
    {
        $this->deletedAt = CheckInDeletedAt::fromDateTimeImmutable(new \DateTimeImmutable());
    }
}
