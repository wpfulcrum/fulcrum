<?php

namespace Fulcrum\Tests\Unit\Extender\Str;

use Fulcrum\Tests\Unit\UnitTestCase;

class ConvertToLowerTest extends UnitTestCase
{
    public function testToLowercase()
    {
        $this->assertEquals('foo bar baz', convert_to_lower_case('FoO baR BAZ'));
        $this->assertEquals('foo bar baz', convert_to_lower_case('FOO BAR BAZ'));

        $this->assertEquals(
            'wordpress community rocks!',
            convert_to_lower_case('WordPress Community Rocks!')
        );
        $this->assertEquals(
            'wordpress wordpress wordpress wordpress',
            convert_to_lower_case('WordPress WORDPRESS wordpress Wordpress')
        );
    }

    public function testToLowercaseNonlatin()
    {
        $string   = 'Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός';
        $expected = 'τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός';
        $this->assertEquals($expected, convert_to_lower_case($string));
        $this->assertEquals('grande árvore', convert_to_lower_case('GRANDE ÁRVORE'));
    }

    public function testToLowercaseUnicode()
    {
        $string   = 'Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός';
        $expected = 'τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός';

        $this->assertNotEquals($expected, convert_to_lower_case($string, null));

        $this->assertNotEquals(
            'grande árvore',
            convert_to_lower_case('GRANDE ÁRVORE', null)
        );
    }

    public function testToLowercaseDifferentEncoding()
    {
        $string   = 'Man is distinguished, not only by his reason, but by this';
        $expected = 'ManisdistinguishedAnotonlybyhisreasonAbutbythis=';

        $this->assertEquals($expected, convert_to_lower_case($string, 'BASE64'));

        $string   = '&lt; HAPPY CODING &gt;';
        $expected = '< happy coding >';

        $this->assertEquals($expected, convert_to_lower_case($string, 'HTML-ENTITIES'));
    }
}
