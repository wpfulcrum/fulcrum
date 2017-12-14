<?php

namespace Fulcrum\Tests\Unit\Extender\Str;

use Fulcrum\Tests\Unit\UnitTestCase;

class ConvertToUpperTest extends UnitTestCase
{
    public function testToUppercase()
    {
        $this->assertEquals('FOO BAR BAZ', convert_to_upper_case('FoO baR BAZ'));
        $this->assertEquals('FOO BAR BAZ', convert_to_upper_case('foo BAR Baz'));

        $this->assertEquals(
            'WORDPRESS COMMUNITY ROCKS!',
            convert_to_upper_case('WordPress Community Rocks!')
        );
        $this->assertEquals('KNOW THE CODE', convert_to_upper_case('Know the Code'));

        $string   = "Mary Had A Little Lamb Who's fleeCe was wHitE as snOw";
        $expected = "MARY HAD A LITTLE LAMB WHO'S FLEECE WAS WHITE AS SNOW";
        $this->assertEquals($expected, convert_to_upper_case($string));
    }

    public function testToUppercaseNonlatin()
    {
        $string   = 'Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός';
        $expected = 'ΤΆΧΙΣΤΗ ΑΛΏΠΗΞ ΒΑΦΉΣ ΨΗΜΈΝΗ ΓΗ, ΔΡΑΣΚΕΛΊΖΕΙ ΥΠΈΡ ΝΩΘΡΟΎ ΚΥΝΌΣ';
        $this->assertEquals($expected, convert_to_upper_case($string));

        $this->assertEquals('GRANDE ÁRVORE', convert_to_upper_case('Grande Árvore'));
        $this->assertEquals('GRANDE ÁRVORE', convert_to_upper_case('Grande árvore'));
    }

    public function testToUppercaseUnicode()
    {
        $string   = 'Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός';
        $expected = 'ΤΆΧΙΣΤΗ ΑΛΏΠΗΞ ΒΑΦΉΣ ΨΗΜΈΝΗ ΓΗ, ΔΡΑΣΚΕΛΊΖΕΙ ΥΠΈΡ ΝΩΘΡΟΎ ΚΥΝΌΣ';

        $this->assertNotEquals($expected, convert_to_upper_case($string, null));
        $this->assertNotEquals(
            'GRANDE ÁRVORE',
            convert_to_upper_case('Grande árvore', null)
        );
    }

    public function testToUppercaseDifferentEncoding()
    {
        $string   = 'Man is distinguished, not only by his reason, but by this';
        $expected = 'ManisdistinguishedAnotonlybyhisreasonAbutbythis=';
        $this->assertEquals($expected, convert_to_upper_case($string, 'BASE64'));

        $string   = '&lt; happy coding &gt;';
        $expected = '< HAPPY CODING >';
        $this->assertEquals($expected, convert_to_upper_case($string, 'HTML-ENTITIES'));
    }
}
