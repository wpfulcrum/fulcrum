<?php

namespace Fulcrum\Tests\Unit\Extender\Arr\Stubs;

class ArrayAccessStub implements \ArrayAccess
{
    public function __construct($config = [])
    {
        if ($config) {
            $this->init($config);
        }
    }

    protected function init(array $config)
    {
        foreach ($config as $property => $value) {
            $this->{$property} = $value;
        }
    }

    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->{$offset};
        }
    }

    public function offsetExists($offset)
    {
        return property_exists($this, $offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->{$offset});
        }
    }
}
