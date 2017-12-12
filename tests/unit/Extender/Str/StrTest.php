<?php

namespace Fulcrum\Tests\Extender\Str;

use Fulcrum\Tests\Unit\UnitTestCase;

class StrTest extends UnitTestCase
{
    public function testGetSubstr()
    {
        $string = 'Fulcrum is disrupting the way you build custom WordPress websites.';
        $this->assertEquals('Fulcrum is disrupting', get_substr($string, 0, 21));
        $this->assertEquals('WordPress websites.', get_substr($string, -19));
        $this->assertEmpty(get_substr($string, 5000));
        $this->assertTrue(get_substr($string, 5000) === '');


        $string = "Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός";
        $this->assertEquals('αλώπηξ', get_substr($string, 8, 6));
        $this->assertEmpty(get_substr($string, 5000));
        $this->assertTrue(get_substr($string, 5000) === '');
    }
}
