<?php declare(strict_types=1);

namespace App\Checking\User\Infrastructure\Persistence\ORM\DataQuery;

use App\Checking\User\Domain\DataQuery\AllUsersDataQuery;
use Doctrine\ORM\EntityManagerInterface;

final readonly class PostgreSQLAllUsersDataQuery implements AllUsersDataQuery
{
    public function __construct(private EntityManagerInterface $entityManager)
    {

    }
    public function execute(): array
    {
        $conn = $this->entityManager->getConnection();

        $sql = '
                SELECT id, name, email
                FROM checking_user
                WHERE deleted_at IS NULL
                ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchAllAssociative();
    }
}
