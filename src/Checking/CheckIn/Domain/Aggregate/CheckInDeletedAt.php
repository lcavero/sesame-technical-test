<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Domain\Aggregate;

use App\Shared\Domain\VO\DateTime\DateTimeOrNullValueObject;

final readonly class CheckInDeletedAt extends DateTimeOrNullValueObject
{

}
