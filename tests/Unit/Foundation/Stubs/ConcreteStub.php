<?php

namespace Fulcrum\Tests\Unit\Foundation\Stubs;

use Fulcrum\Config\ConfigContract;

class ConcreteStub
{
    public $config;

    public function __construct(ConfigContract $config)
    {
        $this->config = $config;
    }
}
