<?php declare(strict_types=1);

namespace App\Tests\Checking\User\Infrastructure\EntryPoint\Api\UpdateUser;

use App\Checking\User\Infrastructure\EntryPoint\Api\UpdateUser\UpdateUserApiRouteParamsRequest;
use App\Tests\BaseKernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdateUserApiRouteParamsRequestTest extends BaseKernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    /** @dataProvider updateUserApiRouteParamsRequestFailuresDataProvider */
    public function testUpdateUserApiRouteParamsRequestFailures(string $id, string $email, array $errors): void
    {
        $request = new UpdateUserApiRouteParamsRequest(
            id: $id,
            email: $email
        );

        $constraintViolationList = $this->validator->validate($request);
        self::assertFalse(0 === $constraintViolationList->count());
        foreach ($constraintViolationList as $constraintViolation) {
            self::assertArrayHasKey($constraintViolation->getPropertyPath(), $errors);
            self::assertSame($errors[$constraintViolation->getPropertyPath()], $constraintViolation->getMessage());
        }
    }

    public function updateUserApiRouteParamsRequestFailuresDataProvider(): array
    {
        return [
            'Id blanks' => [
                'id' => '',
                'email' => '',
                'errors' => [
                    'id' => 'This value should not be blank.',
                ]
            ],
            'Invalid values' => [
                'id' => 'invalid',
                'email' => 'invalid',
                'errors' => [
                    'id' => 'This value is not valid.',
                ]
            ],
        ];
    }
}
