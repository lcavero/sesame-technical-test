<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Infrastructure\Persistence\ORM\DataQuery;

use App\Checking\CheckIn\Domain\DataQuery\CheckInByIdDataQuery;
use Doctrine\ORM\EntityManagerInterface;

final readonly class PostgreSQLCheckInByIdDataQuery implements CheckInByIdDataQuery
{
    public function __construct(private EntityManagerInterface $entityManager)
    {

    }
    public function execute(string $id): ?array
    {
        $conn = $this->entityManager->getConnection();

        $sql = '
                SELECT id, user_id, start_date, end_date, created_at, updated_at, deleted_at
                FROM checking_check_in
                WHERE id = :id
                ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);

        $result = $resultSet->fetchAssociative();
        return false !== $result ? $result : null;
    }
}
