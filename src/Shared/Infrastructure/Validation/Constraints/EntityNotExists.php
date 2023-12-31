<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Validation\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class EntityNotExists extends Constraint
{
    public string $message = 'An entity with {{ field }} equals to "{{ value }}" already exists';

    public function __construct(public string $entity, public string $field, public ?string $excludedEntityField = null, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);
    }
}
