<?php declare(strict_types=1);

namespace App\Tests\Checking\User\Infrastructure\EntryPoint\Api\GetUser;

use App\Checking\User\Infrastructure\EntryPoint\Api\GetUser\GetUserApiRouteParamsRequest;
use App\Tests\BaseKernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class GetUserApiRouteParamsRequestTest extends BaseKernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    /** @dataProvider getUserApiRouteParamsRequestFailuresDataProvider */
    public function testGetUserApiRouteParamsRequestFailures(string $id, array $errors): void
    {
        $request = new GetUserApiRouteParamsRequest(
            id: $id,
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
                'id' => '',
                'errors' => [
                    'id' => 'This value should not be blank.',
                ]
            ],
            'Invalid values' => [
                'id' => 'invalid',
                'errors' => [
                    'id' => 'This value is not valid.',
                ]
            ],
        ];
    }
}
