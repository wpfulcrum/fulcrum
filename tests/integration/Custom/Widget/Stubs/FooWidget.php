<?php

namespace Fulcrum\Tests\Integration\Custom\Widget\Stubs;

use Fulcrum\Custom\Widget\Widget;

class FooWidget extends Widget
{
    public function update($newInstance, $oldInstance)
    {
        $newInstance['class'] = strip_tags($newInstance['class']);

        return $newInstance;
    }
}
