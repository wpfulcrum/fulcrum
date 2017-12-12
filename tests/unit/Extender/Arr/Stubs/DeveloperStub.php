<?php

namespace Fulcrum\Tests\Unit\Extender\Arr\Stubs;

class DeveloperStub
{
    public $name;
    public $email;

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

    public function getName()
    {
        return $this->name;
    }
}
