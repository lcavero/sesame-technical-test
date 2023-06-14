<?php declare(strict_types=1);

namespace App\Checking\User\Infrastructure\Persistence\ORM\DataQuery;

use App\Checking\User\Domain\DataQuery\UserByIdDataQuery;
use Doctrine\ORM\EntityManagerInterface;

final readonly class PostgreSQLUserByIdDataQuery implements UserByIdDataQuery
{
    public function __construct(private EntityManagerInterface $entityManager)
    {

    }
    public function execute(string $id): ?array
    {
        $conn = $this->entityManager->getConnection();

        $sql = '
                SELECT id, name, email, created_at, updated_at, deleted_at
                FROM checking_user
                WHERE id = :id
                ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);

        $result = $resultSet->fetchAssociative();
        return false !== $result ? $result : null;
    }
}
