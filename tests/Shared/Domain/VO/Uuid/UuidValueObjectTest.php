<?php declare(strict_types=1);

namespace App\Tests\Shared\Domain\VO\Uuid;

use App\Shared\Domain\VO\Uuid\InvalidUuidException;
use App\Shared\Domain\VO\Uuid\UuidValueObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class UuidValueObjectTest extends TestCase
{
    public function testGenerateSuccessfully(): void
    {
        self::assertInstanceOf(UuidValueObject::class, UuidValueObject::generate());
    }

    /** @dataProvider fromStringShouldFailDataProvider */
    public function testFromStringShouldFail(string $uuid, string $errorMessage): void
    {
        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionMessage($errorMessage);
        UuidValueObject::fromString($uuid);
    }

    public function testFromStringSuccessfully(): void
    {
        self::assertInstanceOf(UuidValueObject::class, UuidValueObject::fromString(Uuid::v4()->toRfc4122()));
    }

    public function test__toString(): void
    {
        $uuid = Uuid::v4()->toRfc4122();
        self::assertSame($uuid, UuidValueObject::fromString($uuid)->__toString());
    }

    public function fromStringShouldFailDataProvider(): array
    {
        return [
            'Blank' => [
                'uuid' => '',
                'errorMessage' => 'The value "" is not a valid Uuid.'
            ],
            'Invalid #1' => [
                'uuid' => '555',
                'errorMessage' => 'The value "555" is not a valid Uuid.'
            ],
            'Invalid #2' => [
                'uuid' => '4fa97f79-f780-4639-bc0f-3c5f4e3bfadt',
                'errorMessage' => 'The value "4fa97f79-f780-4639-bc0f-3c5f4e3bfadt" is not a valid Uuid.'
            ],
        ];
    }
}
