<?php declare(strict_types=1);

namespace App\Tests\Shared\Domain\VO\String;

use App\Shared\Domain\VO\String\InvalidStringException;
use App\Shared\Domain\VO\String\StringValueObject;
use PHPUnit\Framework\TestCase;

final class StringValueObjectTest extends TestCase
{

    /** @dataProvider fromStringShouldFailDataProvider */
    public function testFromStringShouldFail(string $string, string $errorMessage): void
    {
        $this->expectException(InvalidStringException::class);
        $this->expectExceptionMessage($errorMessage);
        StringValueObject::fromString($string);
    }

    /** @dataProvider successFullyDataProvider */
    public function testFromStringSuccessfully(string $string, string $expected): void
    {
        self::assertSame($expected, StringValueObject::fromString($string)->value);
    }

    /** @dataProvider successFullyDataProvider */
    public function test__toString(string $string, string $expected): void
    {
        self::assertSame($expected, StringValueObject::fromString($string)->__toString());
    }

    public function fromStringShouldFailDataProvider(): array
    {
        return [
            'Lower than min' => [
                'value' => '',
                'errorMessage' => 'The value "" does not meet the minimum length of 1.'
            ],
            'Higher than max' => [
                'value' => 'Es mi repetir llenaba ocultar fe parecio sultana dormian el. Podido pronto es blanco es yo. Tonto una miedo fue apuro rapaz. He no el le escribia acababan quiebras. Querrian pensaban no estupido la. Adjetivo ma comenzar hojuelas el seductor.Cantando la paloma de sr queria.',
                'errorMessage' => 'The value "Es mi repetir llenaba ocultar fe parecio sultana dormian el. Podido pronto es blanco es yo. Tonto una miedo fue apuro rapaz. He no el le escribia acababan quiebras. Querrian pensaban no estupido la. Adjetivo ma comenzar hojuelas el seductor.Cantando la paloma de sr queria." exceeds the maximum length of 255.'
            ],
        ];
    }

    public function successFullyDataProvider(): array
    {
        return [
            'Common string' => [
                'value' => 'Test',
                'expected' => 'Test'
            ],
        ];
    }
}
