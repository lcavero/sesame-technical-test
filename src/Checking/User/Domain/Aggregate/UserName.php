<?php declare(strict_types=1);

namespace App\Checking\User\Domain\Aggregate;

use App\Shared\Domain\VO\String\StringValueObject;

final readonly class UserName extends StringValueObject
{
    const MIN_LENGTH = 3;
    const MAX_LENGTH = 30;
}
