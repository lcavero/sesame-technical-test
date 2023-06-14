<?php declare(strict_types=1);

namespace App\Tests\Checking\CheckIn\Infrastructure\EntryPoint\Api\CreateCheckIn;

use App\Checking\CheckIn\Infrastructure\EntryPoint\Api\CreateCheckIn\CreateCheckInApiRequest;
use App\Checking\User\Domain\Aggregate\User;
use App\Checking\User\Domain\Aggregate\UserCreatedAt;
use App\Checking\User\Domain\Aggregate\UserDeletedAt;
use App\Checking\User\Domain\Aggregate\UserEmail;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Checking\User\Domain\Aggregate\UserName;
use App\Checking\User\Domain\Aggregate\UserUpdatedAt;
use App\Checking\User\Domain\Repository\UserRepository;
use App\Tests\BaseKernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateCheckInApiRequestTest extends BaseKernelTestCase
{
    private ValidatorInterface $validator;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
        $this->userRepository = self::getContainer()->get(UserRepository::class);
    }

    /** @dataProvider createCheckInApiRequestSuccessfullyDataProvider */
    public function testCreateCheckInApiRequestSuccessfully(string $id, string $userId, string $startDate, ?string $endDate): void
    {
        $this->userRepository->save(User::fromValues(
            id: UserId::fromString($userId),
            name: UserName::fromString('Test'),
            email: UserEmail::fromString('test@test.com'),
            createdAt: UserCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            updatedAt: UserUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            deletedAt: UserDeletedAt::fromNull()
        ));

        $request = new CreateCheckInApiRequest(
            id: $id,
            userId: $userId,
            startDate: $startDate,
            endDate: $endDate
        );

        $constraintViolationList = $this->validator->validate($request);
        self::assertTrue(0 === $constraintViolationList->count());
    }

    /** @dataProvider createCheckInApiRequestFailuresDataProvider */
    public function testCreateCheckInApiRequestFailures(string $id, string $userId, string $startDate, ?string $endDate, array $errors): void
    {
        $request = new CreateCheckInApiRequest(
            id: $id,
            userId: $userId,
            startDate: $startDate,
            endDate: $endDate
        );

        $constraintViolationList = $this->validator->validate($request);
        self::assertFalse(0 === $constraintViolationList->count());
        foreach ($constraintViolationList as $constraintViolation) {
            self::assertArrayHasKey($constraintViolation->getPropertyPath(), $errors);
            self::assertSame($errors[$constraintViolation->getPropertyPath()], $constraintViolation->getMessage());
        }
    }

    public function createCheckInApiRequestSuccessfullyDataProvider(): array
    {
        return [
            'Full values' => [
                'id' => 'd0643b8e-1cbb-4f60-84fe-7aa3981297e6',
                'userId' => 'd0643b8e-1cbb-4f60-84fe-7aa3981297e7',
                'startDate' => '2020-05-02 10:00:00',
                'endDate' => '2020-05-02 11:00:00',
            ],
            'Optional values' => [
                'id' => 'd0643b8e-1cbb-4f60-84fe-7aa3981297e6',
                'userId' => 'd0643b8e-1cbb-4f60-84fe-7aa3981297e7',
                'startDate' => '2020-05-02 10:00:00',
                'endDate' => null,
            ],
        ];
    }

    public function createCheckInApiRequestFailuresDataProvider(): array
    {
        return [
            'All blanks' => [
                'id' => '',
                'userId' => '',
                'startDate' => '',
                'endDate' => '',
                'errors' => [
                    'id' => 'This value should not be blank.',
                    'userId' => 'This value should not be blank.',
                    'startDate' => 'This value should not be blank.',
                    'endDate' => 'This value should satisfy at least one of the following constraints: [1] This value should be null. [2] This value should not be blank.',
                ]
            ],
            'Invalid values' => [
                'id' => 'invalid',
                'userId' => 'invalid',
                'startDate' => 'invalid',
                'endDate' => 'invalid',
                'errors' => [
                    'id' => 'This value is not valid.',
                    'userId' => 'This value is not valid.',
                    'startDate' => 'This value is not a valid datetime.',
                    'endDate' => 'This value should satisfy at least one of the following constraints: [1] This value should be null. [2] This value is not a valid datetime.'
                ]
            ],
            'Invalid values #2' => [
                'id' => 'd0643b8e-1cbb-4f60-84fe-7aa3981297e6',
                'userId' => 'd0643b8e-1cbb-4f60-84fe-7aa3981297e7',
                'startDate' => '2020-05-02 10:00:00',
                'endDate' => '2020-05-02 09:00:00',
                'errors' => [
                    'id' => 'This value is not valid.',
                    'userId' => 'An entity with id equals to "d0643b8e-1cbb-4f60-84fe-7aa3981297e7" should exists',
                    'startDate' => 'This value is not valid.',
                    'endDate' => 'This value should satisfy at least one of the following constraints: [1] This value should be null. [2] endDate should be greater than startDate'
                ]
            ],
        ];
    }
}
