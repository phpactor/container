<?php

namespace Phpactor\Container\Tests\Unit;

use Closure;
use Generator;
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
    }

    /**
     * @dataProvider provideCastException
     * @param Closure(Parameter): mixed $invoker
     */
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
    public function provideCastException(): Generator
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
    }
}
