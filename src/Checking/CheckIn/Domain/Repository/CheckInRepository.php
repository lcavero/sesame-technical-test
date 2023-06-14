<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Domain\Repository;

use App\Checking\CheckIn\Domain\Aggregate\CheckIn;
use App\Checking\CheckIn\Domain\Aggregate\CheckInId;

interface CheckInRepository
{
    public function findOneById(CheckInId $id): ?CheckIn;
    public function findOneByIdOrFail(CheckInId $id): CheckIn;
    public function save(CheckIn $checkIn): void;
}
