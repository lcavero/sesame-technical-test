<?php declare(strict_types=1);

namespace App\Tests\Checking\CheckIn\Infrastructure\EntryPoint\Api\ListUserCheckIns;

use App\Checking\CheckIn\Infrastructure\EntryPoint\Api\ListUserCheckIns\ListUserCheckInsApiRouteParamsRequest;
use App\Tests\BaseKernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ListUserCheckInsApiRouteParamsRequestTest extends BaseKernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    /** @dataProvider getUserApiRouteParamsRequestFailuresDataProvider */
    public function testGetUserApiRouteParamsRequestFailures(string $userId, array $errors): void
    {
        $request = new ListUserCheckInsApiRouteParamsRequest(
            userId: $userId,
        );

        $constraintViolationList = $this->validator->validate($request);
        self::assertFalse(0 === $constraintViolationList->count());
        foreach ($constraintViolationList as $constraintViolation) {
            self::assertArrayHasKey($constraintViolation->getPropertyPath(), $errors);
            self::assertSame($errors[$constraintViolation->getPropertyPath()], $constraintViolation->getMessage());
        }
    }

    public function getUserApiRouteParamsRequestFailuresDataProvider(): array
    {
        return [
            'Id blanks' => [
                'userId' => '',
                'errors' => [
                    'userId' => 'This value should not be blank.',
                ]
            ],
            'Invalid values' => [
                'userId' => 'invalid',
                'errors' => [
                    'userId' => 'This value is not valid.',
                ]
            ],
        ];
    }
}
