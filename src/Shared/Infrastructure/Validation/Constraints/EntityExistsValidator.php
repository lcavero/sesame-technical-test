<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Validation\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class EntityExistsValidator extends ConstraintValidator
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof EntityExists) {
            throw new UnexpectedTypeException($constraint, EntityExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (is_array($value) || is_object($value)) {
            throw new UnexpectedValueException($value, 'string|float|int|bool|null');
        }

        $repository = $this->entityManager->getRepository($constraint->entity);
        $entity = $repository->findOneBy([$constraint->field => $value]);

        if (null !== $constraint->excludedEntity) {
            assert(method_exists($entity, 'id'));
        }

        if (null !== $entity && (null === $constraint->excludedEntity || $entity->id()->value !== $constraint->excludedEntity)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ field }}', $constraint->field)
                ->setParameter('{{ value }}', strval($value))
                ->addViolation()
            ;
        }
    }
}
