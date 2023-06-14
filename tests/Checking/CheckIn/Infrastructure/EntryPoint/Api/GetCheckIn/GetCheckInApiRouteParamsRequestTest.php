<?php declare(strict_types=1);

namespace App\Tests\Checking\CheckIn\Infrastructure\EntryPoint\Api\GetCheckIn;

use App\Checking\CheckIn\Infrastructure\EntryPoint\Api\GetCheckIn\GetCheckInApiRouteParamsRequest;
use App\Tests\BaseKernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class GetCheckInApiRouteParamsRequestTest extends BaseKernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    /** @dataProvider getCheckInApiRouteParamsRequestFailuresDataProvider */
    public function testGetCheckInApiRouteParamsRequestFailures(string $id, array $errors): void
    {
        $request = new GetCheckInApiRouteParamsRequest(
            id: $id,
        );

        $constraintViolationList = $this->validator->validate($request);
        self::assertFalse(0 === $constraintViolationList->count());
        foreach ($constraintViolationList as $constraintViolation) {
            self::assertArrayHasKey($constraintViolation->getPropertyPath(), $errors);
            self::assertSame($errors[$constraintViolation->getPropertyPath()], $constraintViolation->getMessage());
        }
    }

    public function getCheckInApiRouteParamsRequestFailuresDataProvider(): array
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
