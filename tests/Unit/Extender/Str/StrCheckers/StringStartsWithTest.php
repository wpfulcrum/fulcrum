<?php

namespace Fulcrum\Tests\Unit\Extender\Str;

use Fulcrum\Tests\Unit\UnitTestCase;
use Fulcrum\Extender\Str\StrCheckers;

class StringStartsWithTest extends UnitTestCase
{
    public function testStartsWithCase()
    {
        $this->assertFalse(StrCheckers::startsWith('Foo', 'f'));
        $this->assertTrue(str_starts_with('Foo', 'F'));

        $this->assertTrue(StrCheckers::startsWith('Checking the Case!', 'Check'));
        $this->assertFalse(str_starts_with('Checking the Case?', 'check'));

        $this->assertFalse(str_starts_with(
            'WordPress Community Rocks!',
            ['wordpress', 'wordPress', 'WORDPRESS']
        ));
        $this->assertTrue(str_starts_with(
            'WordPress Community Rocks!',
            ['wordpress', 'wordPress', 'WORDPRESS', 'WordPress']
        ));
    }

    public function testStartsWith()
    {
        $this->assertTrue(StrCheckers::startsWith('This is a string test.', 'This is'));
        $this->assertFalse(str_starts_with('This is a string test.', 'this is'));
        $this->assertTrue(str_starts_with('This is a string test.', 'This is a '));

        $this->assertFalse(StrCheckers::startsWith('Really dig the Genesis framework! ', 'dig'));
        $this->assertTrue(StrCheckers::startsWith('Really dig the Genesis framework! ', 'Really '));
        $this->assertTrue(str_starts_with('The WordPress Community Rocks!', 'The W'));
        $this->assertFalse(str_starts_with('The WordPress Community Rocks!', ' The '));

        $this->assertTrue(StrCheckers::startsWith(' This is a string test ', ' '));
        $this->assertFalse(str_starts_with('. This is a string test', '.This'));

        $this->assertTrue(StrCheckers::startsWith('SomeClass::someMethod', 'SomeClass::someMethod'));
        $this->assertFalse(str_starts_with('Fulcrum\Extender\Str\StrChecker::startsWith', 'startsWith'));
    }

    public function testStartsWithTypeCasting()
    {
        $this->assertTrue(StrCheckers::startsWith(104, '1'));
        $this->assertFalse(str_starts_with(1247.86, '12486'));
        $this->assertTrue(str_starts_with(1247.86, '1247.'));

        $numbers = [185.3002, 197.002];
        foreach ($numbers as $number) {
            $this->assertTrue(str_starts_with($number, '1'));
            $this->assertFalse(str_starts_with($number, '.0'));
        }

        $this->assertFalse(StrCheckers::startsWith(false, 'ls'));
        $this->assertTrue(str_starts_with(true, '1'));

        $this->assertFalse(str_starts_with(false, '0'));
    }

    public function testStartsWithArrayOfNeedles()
    {
        $this->assertTrue(StrCheckers::startsWith(
            'This is a string test',
            ['this', ' this', ' This', 'This']
        ));
        $this->assertFalse(StrCheckers::startsWith('This is a string test', ['These', 'are']));
        $this->assertTrue(str_starts_with(
            '.... This is a string test',
            ['... This', 'this', '. This', '.... This']
        ));
        $this->assertFalse(str_starts_with(
            '... this is a string test',
            ['this', ' this', '.. this', '...this']
        ));

        $this->assertTrue(StrCheckers::startsWith('Hello from Tonya', ['Hello', 'Tonya']));
        $this->assertFalse(StrCheckers::startsWith('Hello from Tonya', ['hello', 'From']));
        $this->assertTrue(str_starts_with(
            'WordPress Community Rocks!',
            ['wordpress', 'wordPress', 'WordPress', 'WORDPRESS']
        ));

        $this->assertFalse(StrCheckers::startsWith(
            'The WordPress Community Rocks!',
            ['the', 'WordPress', 'Community', 'Rocks!']
        ));
        $this->assertTrue(str_starts_with(
            'The WordPress Community Rocks!',
            ['The WordPress c', 'The WordPress']
        ));
    }

    public function testStartsWithNonLatin()
    {
        $stringToConvert = "Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός";

        $this->assertTrue(StrCheckers::startsWith($stringToConvert, 'Τάχιστη'));
        $this->assertFalse(str_starts_with($stringToConvert, 'Δρασκελίζει'));

        $this->assertTrue(StrCheckers::startsWith($stringToConvert, 'Τάχιστη αλώπηξ βαφής ψημένη γη,'));
        $this->assertTrue(str_starts_with($stringToConvert, 'Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκε'));

        $this->assertFalse(StrCheckers::startsWith($stringToConvert, ['tάχιστη', 'υπέρ']));
        $this->assertFalse(str_starts_with($stringToConvert, ['Υπέρ', 'ψημένη']));

        $this->assertTrue(StrCheckers::startsWith($stringToConvert, ['tάχιστη', 'Τάχιστη', 'κυνός']));
        $this->assertTrue(str_starts_with($stringToConvert, ['Υπέρ', 'ψημένη', 'Τάχιστη ']));
    }
}
