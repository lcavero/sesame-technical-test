<?php declare(strict_types=1);

namespace App\Checking\User\Domain\Repository;

use App\Shared\Domain\Exception\DomainException;

final class UserNotFoundException extends DomainException
{
}
