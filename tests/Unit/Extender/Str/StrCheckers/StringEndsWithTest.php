<?php

namespace Fulcrum\Tests\Unit\Extender\Str;

use Fulcrum\Tests\Unit\UnitTestCase;
use Fulcrum\Extender\Str\StrCheckers;

class StringEndsWithTest extends UnitTestCase
{

    public function testEndsWithCase()
    {
        $this->assertFalse(StrCheckers::endsWith('Foo', 'O'));
        $this->assertTrue(str_ends_with('Foo', 'o'));

        $this->assertTrue(StrCheckers::endsWith('Checking the Case!', 'Case!'));
        $this->assertFalse(str_ends_with('Checking the Case?', 'case?'));
    }

    public function testEndsWith()
    {
        $this->assertTrue(StrCheckers::endsWith('This is a string test.', '.'));
        $this->assertFalse(str_ends_with('This is a string test.', 'test'));
        $this->assertTrue(str_ends_with('This is a string test.', 'test.'));

        $this->assertFalse(StrCheckers::endsWith('Really dig the Genesis framework! ', 'framework!'));
        $this->assertTrue(StrCheckers::endsWith('Really dig the Genesis framework! ', 'framework! '));
        $this->assertTrue(str_ends_with('The WordPress Community Rocks!', 'Rocks!'));
        $this->assertFalse(str_ends_with('The WordPress Community Rocks!', 'rocks!'));

        $this->assertTrue(StrCheckers::endsWith('This is a string test ', ' '));
        $this->assertFalse(str_ends_with('This is a string test', ' '));

        $this->assertTrue(StrCheckers::endsWith('SomeClass::someMethod', 'd'));
        $this->assertFalse(str_ends_with('Fulcrum\Extender\Str\StrChecker::endsWith', '/'));
    }

    public function testEndsWithTypeCasting()
    {
        $this->assertTrue(StrCheckers::endsWith(104, '04'));
        $this->assertFalse(str_ends_with(1247.86, '5'));
        $this->assertTrue(str_ends_with(1247.86, '.86'));

        $numbers = [85.3002, 97.002];
        foreach ($numbers as $number) {
            $this->assertTrue(str_ends_with($number, '002'));
            $this->assertFalse(str_ends_with($number, '.0'));
        }

        $this->assertFalse(StrCheckers::endsWith(false, 'ls'));
        $this->assertTrue(str_ends_with(true, '1'));

        $this->assertFalse(str_ends_with(false, '0'));
        $this->assertTrue(str_ends_with(false, ''));
    }

    public function testEndsWithArrayOfNeedles()
    {
        $this->assertTrue(StrCheckers::endsWith('This is a string test', ['Test', 'test']));
        $this->assertFalse(StrCheckers::endsWith('This is a string test', ['string', 'are']));
        $this->assertTrue(str_ends_with('This is a string test', ['tests', 'test!', 'Test', 'test']));
        $this->assertFalse(str_ends_with('This is a string test', ['tests', 'Tests', 'tests!', 'Tests']));

        $this->assertTrue(StrCheckers::endsWith('Hello from Tonya', ['From', 'Tonya']));
        $this->assertFalse(StrCheckers::endsWith('Hello from Tonya', ['hello', 'From']));
        $this->assertFalse(str_ends_with(
            'The WordPress Community Rocks!',
            ['wordpress', 'wordPress', 'WordPress', 'WORDPRESS']
        ));

        $this->assertFalse(StrCheckers::endsWith(
            'The WordPress Community Rocks!',
            ['rocks!', 'community', 'WordPress', 'the']
        ));
        $this->assertTrue(str_ends_with(
            'The WordPress Community Rocks!',
            ['community rocks!', 'unity Rocks!']
        ));
    }

    public function testEndsWithNonLatin()
    {
        $stringToConvert = "Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός";

        $this->assertFalse(StrCheckers::endsWith($stringToConvert, ' '));
        $this->assertFalse(str_ends_with($stringToConvert, ' '));

        $this->assertTrue(StrCheckers::endsWith($stringToConvert, 'ς'));
        $this->assertFalse(str_ends_with($stringToConvert, 'Δρασκελίζει'));

        $this->assertTrue(StrCheckers::endsWith($stringToConvert, 'ρού κυνός'));
        $this->assertTrue(str_ends_with($stringToConvert, 'δρασκελίζει υπέρ νωθρού κυνός'));

        $this->assertFalse(StrCheckers::endsWith($stringToConvert, ['tάχιστη', 'υπέρ']));
        $this->assertFalse(str_ends_with($stringToConvert, ['Υπέρ', 'ψημένη']));

        $this->assertTrue(StrCheckers::endsWith($stringToConvert, ['tάχιστη', 'υπέρ', 'κυνός']));
        $this->assertTrue(str_ends_with($stringToConvert, ['Υπέρ', 'ψημένη', 'ρού κυνός']));
    }
}
