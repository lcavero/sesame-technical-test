<?php declare(strict_types=1);

namespace App\Checking\User\Infrastructure\Persistence\ORM\Repository;

use App\Checking\User\Domain\Aggregate\User;
use App\Checking\User\Domain\Aggregate\UserEmail;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Checking\User\Domain\Exception\UserNotFoundException;
use App\Checking\User\Domain\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineUserRepository extends ServiceEntityRepository implements UserRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findOneById(UserId $id): ?User
    {
        $em = $this->getEntityManager();
        return $em->find(User::class, $id->value);
    }

    public function findOneByIdOrFail(UserId $id): User
    {
        $em = $this->getEntityManager();
        $user = $em->find(User::class, $id->value);
        if (null === $user) {
            throw UserNotFoundException::create(sprintf('The user with id "%s" was not found.', $id->value));
        }
        return $user;
    }

    public function findOneByEmail(UserEmail $email): ?User
    {
        $em = $this->getEntityManager();
        return $em->getRepository(User::class)->findOneBy(['email.value' => $email->value]);
    }

    public function save(User $user): void
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }
}
