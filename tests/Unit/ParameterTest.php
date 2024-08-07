<?php

namespace Phpactor\Container\Tests\Unit;

use Closure;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Phpactor\Container\Parameter;
use RuntimeException;

class ParameterTest extends TestCase
{
    public function testCast(): void
    {
        self::assertSame(12, (new Parameter(12))->value());
        self::assertSame(12, (new Parameter(12))->int());
        self::assertSame('12', (new Parameter('12'))->string());
        self::assertSame(true, (new Parameter(true))->bool());
        self::assertSame(12.2, (new Parameter(12.2))->float());
        self::assertSame(12.0, (new Parameter(12))->float());

        self::assertSame(null, (new Parameter(null))->intOrNull());
        self::assertSame(null, (new Parameter(null))->floatOrNull());
        self::assertSame(null, (new Parameter(null))->stringOrNull());
        self::assertSame(null, (new Parameter(null))->boolOrNull());

        self::assertSame(1, (new Parameter(1))->intOrNull());
        self::assertSame(1.1, (new Parameter(1.1))->floatOrNull());
        self::assertSame('one', (new Parameter('one'))->stringOrNull());
        self::assertSame(true, (new Parameter(true))->boolOrNull());

        self::assertSame(['foo'], (new Parameter(['foo']))->listOfString());
        self::assertSame(['foo'], (new Parameter('foo'))->listOfString());
        self::assertSame(['1'], (new Parameter(1))->listOfString());

        self::assertSame([1.2], (new Parameter(['1.2']))->listOfFloat());
        self::assertSame([1.4], (new Parameter(1.4))->listOfFloat());
        self::assertSame([1.2], (new Parameter([1.2]))->listOfFloat());

        self::assertSame([1], (new Parameter(['1']))->listOfInt());
        self::assertSame([2], (new Parameter(2))->listOfInt());
        self::assertSame([2], (new Parameter([2]))->listOfInt());

        self::assertSame([true], (new Parameter(['1']))->listOfBool());
        self::assertSame([false], (new Parameter(false))->listOfBool());
        self::assertSame([true], (new Parameter([true]))->listOfBool());
    }

    /**
     * @param Closure(Parameter): mixed $invoker
     */
    #[DataProvider('provideCastException')]
    public function testCastException(mixed $value, Closure $invoker, string $message): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage($message);
        $invoker(
            new Parameter($value)
        );
    }
    /**
     * @return Generator<string,array{mixed,Closure(Parameter): mixed,string}>
     */
    public static function provideCastException(): Generator
    {
        yield 'not an int' => [
            '12',
            fn (Parameter $p) => $p->int(),
            'Value is not an int, it is "string"'
        ];
        yield 'not an string' => [
            12,
            fn (Parameter $p) => $p->string(),
            'Value is not a string, it is "integer"'
        ];
        yield 'not a float' => [
            '12',
            fn (Parameter $p) => $p->float(),
            'Value is not a float, it is "string"'
        ];
        yield 'not a bool' => [
            '12',
            fn (Parameter $p) => $p->bool(),
            'Value is not a bool, it is "string"'
        ];

        yield 'list of string with list of string' => [
            [[]],
            fn (Parameter $p) => $p->listOfString(),
            'Value in list is not a scalar, it is an "array"'
        ];
    }
}
