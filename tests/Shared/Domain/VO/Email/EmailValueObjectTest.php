<?php declare(strict_types=1);

namespace Shared\Domain\VO\Email;

use App\Shared\Domain\VO\Email\EmailValueObject;
use App\Shared\Domain\VO\Email\InvalidEmailException;
use PHPUnit\Framework\TestCase;

final class EmailValueObjectTest extends TestCase
{
    /** @dataProvider fromStringSuccessfullyDataProvider */
    public function testFromStringSuccessfully(string $email): void
    {
        self::assertSame($email, EmailValueObject::fromString($email)->value);
    }

    /** @dataProvider fromStringShouldFailDataProvider */
    public function testFromStringShouldFail(string $email, string $errorMessage): void
    {
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessage($errorMessage);
        EmailValueObject::fromString($email);
    }

    /** @dataProvider toStringDataProvider */
    public function test__toString(string $email): void
    {
        self::assertSame($email, EmailValueObject::fromString($email)->__toString());
    }

    public function fromStringShouldFailDataProvider(): array
    {
        return [
            'Invalid email #1' => [
                'email' => 'a@ a',
                'errorMessage' => 'The value "a@ a" is not a valid email.'
            ],
            'Invalid email #2' => [
                'email' => 'a@.com',
                'errorMessage' => 'The value "a@.com" is not a valid email.'
            ],
            'Invalid email #3' => [
                'email' => 'a@_.com',
                'errorMessage' => 'The value "a@_.com" is not a valid email.'
            ],
        ];
    }

    public function toStringDataProvider(): array
    {
        return [
            'Valid email' => [
                'email' => 'valid-email@gmail.com'
            ]
        ];
    }

    public function fromStringSuccessfullyDataProvider(): array
    {
        return [
            'Valid email' => [
                'email' => 'valid-email@gmail.com'
            ]
        ];
    }
}
