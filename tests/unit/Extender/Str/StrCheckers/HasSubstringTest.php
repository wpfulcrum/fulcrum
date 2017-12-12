<?php

namespace Fulcrum\Tests\Unit\Extender\Str;

use Fulcrum\Tests\Unit\UnitTestCase;
use Fulcrum\Extender\Str\StrCheckers;

class HasSubstringTest extends UnitTestCase
{
    public function testHasSubstringCase()
    {
        $this->assertFalse(StrCheckers::hasSubstring('Foo', 'foo'));
        $this->assertTrue(StrCheckers::hasSubstring('Foo', 'Foo'));

        $this->assertFalse(StrCheckers::hasSubstring('Checking the Case', 'checking the'));
        $this->assertTrue(StrCheckers::hasSubstring('Checking the Case', 'the Case'));
    }

    public function testHasSubstring()
    {

        $this->assertTrue(StrCheckers::hasSubstring('This is a string test', 'string'));
        $this->assertFalse(has_substring('This is a string test', 'foo'));

        $this->assertTrue(StrCheckers::hasSubstring('This is a string test', 'This is'));
        $this->assertFalse(StrCheckers::hasSubstring('This is a string test', 'This is foo'));
        $this->assertTrue(has_substring('This is a string test', 'string test'));

        $this->assertFalse(StrCheckers::hasSubstring('Really dig the Genesis framework', 'GenesisWP'));
        $this->assertTrue(StrCheckers::hasSubstring('Really dig the Genesis framework', 'Genesis framework'));
        $this->assertTrue(has_substring('The WordPress Community Rocks!', 'Rocks!'));
        $this->assertFalse(has_substring('The WordPress Community Rocks!', 'rocks!'));

        $this->assertTrue(StrCheckers::hasSubstring('This is a string test', ' '));
        $this->assertTrue(has_substring('This is a string test', ' '));

        $this->assertTrue(StrCheckers::hasSubstring('SomeClass::someMethod', '::'));
        $this->assertFalse(has_substring('Fulcrum\Extender\Str\StrChecker::hasSubstring', '/'));
    }

    public function testHasSubstringTypeCasting()
    {
        $this->assertTrue(StrCheckers::hasSubstring(104, '0'));
        $this->assertFalse(has_substring(1247.86, '5'));
        $this->assertTrue(has_substring(1247.86, '.86'));

        $numbers = [85.3, 97.002];
        foreach ($numbers as $number) {
            $this->assertTrue(has_substring($number, '.'));
        }

        $this->assertFalse(StrCheckers::hasSubstring(false, 'ls'));
        $this->assertTrue(has_substring(true, '1'));

        $this->assertFalse(has_substring(false, '0'));
        $this->assertFalse(has_substring(false, ''));
    }

    public function testHasSubstringArrayOfNeedles()
    {
        $this->assertTrue(StrCheckers::hasSubstring('This is a string test', ['This', 'is']));
        $this->assertFalse(StrCheckers::hasSubstring('This is a string test', ['These', 'are']));
        $this->assertTrue(has_substring('This is a string test', ['strings', 'test']));
        $this->assertTrue(has_substring('This is a string test', ['These', 'string']));
        $this->assertFalse(has_substring('This is a string test', ['These', 'tests']));

        $this->assertTrue(StrCheckers::hasSubstring('Hello from Tonya', ['From', 'Tonya']));
        $this->assertFalse(StrCheckers::hasSubstring('Hello from Tonya', ['hello', 'From']));
        $this->assertTrue(has_substring(
            'The WordPress Community Rocks!',
            ['wordpress', 'wordPress', 'WordPress', 'WORDPRESS']
        ));

        $this->assertTrue(StrCheckers::hasSubstring(
            'The WordPress Community Rocks!',
            ['rocks!', 'community', 'WordPress', 'the']
        ));
        $this->assertTrue(has_substring('The WordPress Community Rocks!', ['rocks!', 'community', 'WordPress', 'the']));
    }

    public function testHasSubstringNonLatin()
    {
        $stringToConvert = "Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός";

        $this->assertTrue(StrCheckers::hasSubstring($stringToConvert, ' '));
        $this->assertTrue(has_substring($stringToConvert, ' '));

        $this->assertFalse(StrCheckers::hasSubstring($stringToConvert, 'tάχιστη'));
        $this->assertFalse(has_substring($stringToConvert, 'Δρασκελίζει'));

        $this->assertTrue(StrCheckers::hasSubstring($stringToConvert, 'Τάχιστη αλώπηξ'));
        $this->assertTrue(has_substring($stringToConvert, 'δρασκελίζει υπέρ'));

        $this->assertTrue(StrCheckers::hasSubstring($stringToConvert, ['tάχιστη', 'νωθρού']));
        $this->assertTrue(has_substring($stringToConvert, ['Υπέρ', 'ψημένη']));
    }
}
