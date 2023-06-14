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
    public function testUpdateUserApiRequestFailures(string $name, string $email, array $errors): void
    {
        $request = new UpdateUserApiRequest(
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
                'name' => '',
                'email' => '',
                'errors' => [
                    'name' => 'This value should not be blank.',
                    'email' => 'This value should not be blank.',
                ]
            ],
            'Invalid values' => [
                'name' => '1',
                'email' => 'invalid',
                'errors' => [
                    'name' => "This value is too short. It should have 3 characters or more.",
                    'email' => 'This value is not valid.',
                ]
            ],
            'Invalid values #2' => [
                'name' => 'TestFieldWithMoreThan30Characters',
                'email' => '-',
                'errors' => [
                    'name' => "This value is too long. It should have 30 characters or less.",
                    'email' => 'This value is not valid.',
                ]
            ],
        ];
    }
}
