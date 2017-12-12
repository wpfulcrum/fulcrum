<?php

namespace Fulcrum\Tests\Unit\Extender\Str\Stubs;

class StringableObjectStub
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
