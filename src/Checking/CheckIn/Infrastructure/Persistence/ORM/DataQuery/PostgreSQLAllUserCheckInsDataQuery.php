<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Infrastructure\Persistence\ORM\DataQuery;

use App\Checking\CheckIn\Domain\DataQuery\AllUserCheckInsDataQuery;
use App\Checking\User\Domain\DataQuery\AllUsersDataQuery;
use Doctrine\ORM\EntityManagerInterface;

final readonly class PostgreSQLAllUserCheckInsDataQuery implements AllUserCheckInsDataQuery
{
    public function __construct(private EntityManagerInterface $entityManager)
    {

    }
    public function execute(string $userId): array
    {
        $conn = $this->entityManager->getConnection();

        $sql = '
                SELECT id, user_id, start_date, end_date
                FROM checking_check_in
                WHERE deleted_at IS NULL
                AND user_id = :userId
                ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['userId' => $userId]);

        return $resultSet->fetchAllAssociative();
    }
}
