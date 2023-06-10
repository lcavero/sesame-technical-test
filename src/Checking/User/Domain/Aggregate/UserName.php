<?php declare(strict_types=1);

namespace App\Checking\User\Domain\Aggregate;

use App\Shared\Domain\VO\String\StringValueObject;

final readonly class UserName extends StringValueObject
{
    const MIN = 3;
    const MAX = 30;
}
