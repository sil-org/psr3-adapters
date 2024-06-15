<?php

namespace Sil\Psr3Adapters\tests;

class StringableObject implements \Stringable
{
    private string $a;
    private string $b;

    public function __construct(string $a = '', string $b = '')
    {
        $this->a = $a;
        $this->b = $b;
    }

    public function __toString(): string
    {
        return $this->a . $this->b;
    }
}
