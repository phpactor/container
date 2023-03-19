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
}
