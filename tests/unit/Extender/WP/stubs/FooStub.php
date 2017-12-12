<?php

namespace Fulcrum\Tests\Unit\Extender\WP;

class FooStub
{
    public $ID;

    public function __construct($id = 0)
    {
        $this->ID = $id;
    }
}
