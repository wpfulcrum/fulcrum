<?php

namespace Fulcrum\Tests\Unit\Extender\Str;

use Fulcrum\Tests\Unit\UnitTestCase;
use Fulcrum\Extender\Str\StrCheckers;
use Fulcrum\Tests\Unit\Extender\Str\Stubs\StringableObjectStub;

class MatchesPatternTest extends UnitTestCase
{
    public function testMatchesPatternCase()
    {
        $this->assertFalse(StrCheckers::matchesPattern('Foo/', 'foo/'));
        $this->assertTrue(StrCheckers::matchesPattern('Foo/', 'Foo/'));

        $this->assertFalse(str_matches_wildcard('Foo/', 'foo/'));
    }

    public function testBasics()
    {
        $this->assertTrue(StrCheckers::matchesPattern('/', '/'));
        $this->assertFalse(StrCheckers::matchesPattern('/', ' /'));

        $this->assertFalse(str_matches_wildcard('/', '/a'));

        $this->assertTrue(StrCheckers::matchesPattern('foo/*', 'foo/bar/baz'));
        $this->assertTrue(str_matches_wildcard('*/foo', 'blah/baz/foo'));

        $this->assertFalse(str_matches_wildcard('baz*', 'foobar'));
        $this->assertTrue(StrCheckers::matchesPattern('*bar/*', 'foo/bar/baz'));
    }

    public function testMatchesPattern()
    {
        $this->assertTrue(StrCheckers::matchesPattern(
            '*foo*',
            'This string has an emphasis on *foo*.'
        ));
        $this->assertTrue(str_matches_wildcard(
            'Lore* ip*um dolor s* amet',
            'Lorem ipsum dolor sit amet'
        ));
        $this->assertFalse(StrCheckers::matchesPattern('WordPress*', 'wordpress community rocks'));

        $this->assertTrue(str_matches_wildcard('*def*', 'abcdef'));

        $this->assertTrue(StrCheckers::matchesPattern('Fulcrum\Tests\Unit\Extender\*', __NAMESPACE__));
        $this->assertFalse(str_matches_wildcard('MatchesPatternTest*', __CLASS__));
        $this->assertTrue(StrCheckers::matchesPattern('*MatchesPatternTest', __CLASS__));

        $this->assertFalse(StrCheckers::matchesPattern('test_*', __METHOD__));
        $this->assertTrue(str_matches_wildcard('*test*', __METHOD__));
    }

    public function testMatchesPatternNonLatin()
    {
        $string = "Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός";

        $this->assertTrue(StrCheckers::matchesPattern('Τάχιστη*', $string));
        $this->assertFalse(StrCheckers::matchesPattern('*tάχιστη*', $string));

        $this->assertTrue(str_matches_wildcard('Τάχιστη αλώπηξ βαφής ψημένη γη*', $string));
    }

    public function testObject()
    {
        $valueObject   = new StringableObjectStub('foo/bar/baz');
        $patternObject = new StringableObjectStub('foo/*');

        $this->assertTrue(StrCheckers::matchesPattern('foo/bar/baz', $valueObject));
        $this->assertTrue(StrCheckers::matchesPattern($patternObject, $valueObject));
    }
}
