<?php

namespace Phpactor\Container;

use RuntimeException;

final class Parameter
{
    public function __construct(private mixed $value)
    {
    }

    public function value(): mixed
    {
        return $this->value;
    }

    public function int(): int
    {
        if (!is_int($this->value)) {
            throw new RuntimeException(sprintf('Value is not an int, it is "%s"', gettype($this->value)));
        }
        return $this->value;
    }

    public function string(): string
    {
        if (!is_string($this->value)) {
            throw new RuntimeException(sprintf('Value is not a string, it is "%s"', gettype($this->value)));
        }
        return $this->value;
    }

    public function bool(): bool
    {
        if (!is_bool($this->value)) {
            throw new RuntimeException(sprintf('Value is not a bool, it is "%s"', gettype($this->value)));
        }
        return $this->value;
    }

    public function float(): float
    {
        if (is_int($this->value)) {
            return (float)$this->value;
        }
        if (!is_float($this->value)) {
            throw new RuntimeException(sprintf('Value is not a float, it is "%s"', gettype($this->value)));
        }
        return $this->value;
    }

    /**
     * @return list<string>
     */
    public function listOfString(): array
    {
        return array_map(function (mixed $value) {
            $this->assertScalar($value);
            return (string)$value;
        }, (array)$this->value);
    }

    /**
     * @return list<int>
     */
    public function listOfInt(): array
    {
        return array_map(function (mixed $value) {
            $this->assertScalar($value);
            return (int)$value;
        }, (array)$this->value);
    }

    /**
     * @return list<float>
     */
    public function listOfFloat(): array
    {
        return array_map(function (mixed $value) {
            $this->assertScalar($value);
            return (float)$value;
        }, (array)$this->value);
    }

    /**
     * @return list<bool>
     */
    public function listOfBool(): array
    {
        return array_map(function (mixed $value) {
            $this->assertScalar($value);
            return (bool)$value;
        }, (array)$this->value);
    }

    public function intOrNull(): ?int
    {
        if (null === $this->value) {
            return null;
        }

        return $this->int();
    }

    public function floatOrNull(): ?float
    {
        if (null === $this->value) {
            return null;
        }

        return $this->float();
    }

    public function stringOrNull(): ?string
    {
        if (null === $this->value) {
            return null;
        }

        return $this->string();
    }

    public function boolOrNull(): ?bool
    {
        if (null === $this->value) {
            return null;
        }

        return $this->bool();
    }

    /**
     * @phpstan-assert scalar $value
     */
    private function assertScalar(mixed $value): void
    {
        if (!is_scalar($value)) {
            throw new RuntimeException(sprintf('Value in list is not a scalar, it is an "%s"', gettype($this->value)));
        }
    }

}
