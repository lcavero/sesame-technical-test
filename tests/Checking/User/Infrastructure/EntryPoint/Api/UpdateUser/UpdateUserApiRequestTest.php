<?php declare(strict_types=1);

namespace App\Tests\Checking\User\Infrastructure\EntryPoint\Api\UpdateUser;

use App\Checking\User\Infrastructure\EntryPoint\Api\UpdateUser\UpdateUserApiRequest;
use App\Tests\BaseKernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdateUserApiRequestTest extends BaseKernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    /** @dataProvider updateUserApiRequestFailuresDataProvider */
    public function testUpdateUserApiRequestFailures(string $id, string $name, string $email, array $errors): void
    {
        $request = new UpdateUserApiRequest(
            id: $id,
            name: $name,
            email: $email
        );

        $constraintViolationList = $this->validator->validate($request);
        self::assertFalse(0 === $constraintViolationList->count());
        foreach ($constraintViolationList as $constraintViolation) {
            self::assertArrayHasKey($constraintViolation->getPropertyPath(), $errors);
            self::assertSame($errors[$constraintViolation->getPropertyPath()], $constraintViolation->getMessage());
        }
    }

    public function updateUserApiRequestFailuresDataProvider(): array
    {
        return [
            'All blanks' => [
                'id' => '',
                'name' => '',
                'email' => '',
                'errors' => [
                    'id' => 'This value should not be blank.',
                    'name' => 'This value should not be blank.',
                    'email' => 'This value should not be blank.',
                ]
            ],
            'Invalid values' => [
                'id' => 'invalid',
                'name' => '1',
                'email' => 'invalid',
                'errors' => [
                    'id' => 'This value is not valid.',
                    'name' => "This value is too short. It should have 3 characters or more.",
                    'email' => 'This value is not valid.',
                ]
            ],
            'Invalid values #2' => [
                'id' => 'uuid',
                'name' => 'TestFieldWithMoreThan30Characters',
                'email' => '-',
                'errors' => [
                    'id' => 'This value is not valid.',
                    'name' => "This value is too long. It should have 30 characters or less.",
                    'email' => 'This value is not valid.',
                ]
            ],
        ];
    }
}
