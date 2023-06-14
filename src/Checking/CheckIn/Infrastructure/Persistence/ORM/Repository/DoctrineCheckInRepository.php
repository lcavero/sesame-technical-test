<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Infrastructure\Persistence\ORM\Repository;

use App\Checking\CheckIn\Domain\Repository\CheckInRepository;
use App\Checking\CheckIn\Domain\Aggregate\CheckIn;
use App\Checking\CheckIn\Domain\Aggregate\CheckInId;
use App\Checking\CheckIn\Domain\Exception\CheckInNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineCheckInRepository extends ServiceEntityRepository implements CheckInRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CheckIn::class);
    }

    public function findOneById(CheckInId $id): ?CheckIn
    {
        $em = $this->getEntityManager();
        return $em->find(CheckIn::class, $id->value);
    }

    public function findOneByIdOrFail(CheckInId $id): CheckIn
    {
        $em = $this->getEntityManager();
        $checkIn = $em->find(CheckIn::class, $id->value);
        if (null === $checkIn) {
            throw CheckInNotFoundException::create(sprintf('The check-in with id "%s" was not found.', $id->value));
        }
        return $checkIn;
    }

    public function save(CheckIn $checkIn): void
    {
        $em = $this->getEntityManager();
        $em->persist($checkIn);
        $em->flush();
    }
}
