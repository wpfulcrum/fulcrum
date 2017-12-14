<?php

namespace Fulcrum\Tests\Unit\Extender\Str;

use Fulcrum\Tests\Unit\UnitTestCase;
use Fulcrum\Extender\Str\StrConverters;

class ConvertToTest extends UnitTestCase
{
    public function testToStudly()
    {
        $this->assertEquals('FooBar', StrConverters::toStudlyCase('foo_bar'));
        $this->assertEquals('FooBar', StrConverters::toStudlyCase('foo-Bar'));
        $this->assertEquals('FooBar', StrConverters::toStudlyCase('foo bar'));
        $this->assertEquals('FooBar', StrConverters::toStudlyCase('foo _ bar'));
        $this->assertEquals('FooBar', StrConverters::toStudlyCase('foo -_- bar'));

        $this->assertEquals('ElePhpant', convert_to_studly_case('ele_phpant'));
        $this->assertEquals('ElePHPant', convert_to_studly_case('ele_p_h_pant'));
        $this->assertEquals('ElePhpAnt', convert_to_studly_case('ele pHP ant'));

        $this->assertEquals('WordPress', convert_to_studly_case('word press'));
        $this->assertEquals('WordPress', convert_to_studly_case('word_press'));
        $this->assertEquals('Wordpress', convert_to_studly_case('wordPress'));

        $string   = 'Education is what remains after one has forgotten what one has learned in school.';
        $expected = 'EducationIsWhatRemainsAfterOneHasForgottenWhatOneHasLearnedInSchool.';
        $this->assertEquals($expected, convert_to_studly_case($string));

        $this->assertEquals('WpQuery', StrConverters::toStudlyCase('WP_Query'));
        $this->assertEquals('StrConverters', StrConverters::toStudlyCase('Str_Converters'));
    }

    public function testToCamelCase()
    {
        $this->assertEquals('fooBar', StrConverters::toCamelCase('foo_bar'));
        $this->assertEquals('foobar', convert_to_camel_case('FooBar'));
        $this->assertEquals('fooBar', convert_to_camel_case('Foo Bar'));

        $this->assertEquals('elePhpant', StrConverters::toCamelCase('ele_phpant'));
        $this->assertEquals('elePHPant', convert_to_camel_case('ele_p_h_pant'));
        $this->assertEquals('elePhpAnt', StrConverters::toCamelCase('ele pHP ant'));

        $this->assertEquals('wordPress', convert_to_camel_case('word press'));
        $this->assertEquals('wordPress', StrConverters::toCamelCase('word_press'));
        $this->assertEquals('wordpress', convert_to_camel_case('wordPress'));

        $this->assertEquals('convertToCamelCase', StrConverters::toCamelCase('convert_to_camel_case'));
        $this->assertEquals('getTheId', StrConverters::toCamelCase('get_the_ID'));
        $this->assertEquals('getPostMeta', StrConverters::toCamelCase('get_post_meta'));
    }

    public function testToSnakeCase()
    {
        $this->assertEquals('foo_bar', convert_to_snake_case('foo bar'));
        $this->assertEquals('foo_bar', StrConverters::toSnakeCase('Foo Bar'));
        $this->assertEquals('foo_bar', convert_to_snake_case('FooBar'));
        $this->assertEquals('foo_bar', convert_to_snake_case('fooBar'));
        $this->assertEquals('foo-_bar', StrConverters::toSnakeCase('Foo-Bar'));
        $this->assertEquals('foo__bar', convert_to_snake_case('Foo_Bar'));

        $this->assertEquals('foo_bar_baz', StrConverters::toSnakeCase('Foo Bar Baz'));
        $this->assertEquals('foo_bar_baz', convert_to_snake_case('FooBarBaz'));
        $this->assertEquals('foo_bar_baz', convert_to_snake_case('fooBarBaz'));
        $this->assertEquals('foo__bar__baz', StrConverters::toSnakeCase('Foo_Bar_Baz'));

        $this->assertEquals('word_press', convert_to_snake_case('word press'));
        $this->assertEquals('word_press', StrConverters::toSnakeCase('wordPress'));
        $this->assertEquals('word_press', convert_to_snake_case('WordPress'));
        $this->assertEquals('word-_press', convert_to_snake_case('Word-Press'));

        $this->assertEquals('convert_to_snake_case', convert_to_snake_case('convertToSnakeCase'));
        $this->assertEquals('get_the_id', StrConverters::toSnakeCase('getTheId'));
        $this->assertEquals('get_the_i_d', convert_to_snake_case('getTheID'));
        $this->assertEquals('get_post_meta', StrConverters::toSnakeCase('getPostMeta'));

        // test single words
        $this->assertEquals('foobar', convert_to_snake_case('foobar'));
    }

    public function testToUnderscore()
    {
        $this->assertEquals('foo_bar', convert_to_underscore('foo bar'));
        $this->assertEquals('foo_bar', convert_to_underscore('foo_Bar'));
        $this->assertEquals('foo_bar', StrConverters::toUnderscore('Foo Bar'));
        $this->assertEquals('foo_bar_baz', StrConverters::toUnderscore('Foo Bar_baz  '));
        $this->assertEquals('foo_bar', convert_to_underscore('FooBar'));
        $this->assertEquals('foo_bar', convert_to_underscore('fooBar'));
        $this->assertEquals('foo_bar', StrConverters::toUnderscore('Foo-Bar'));
        $this->assertEquals('foo_bar', convert_to_underscore('Foo_Bar'));

        $this->assertEquals('foo_bar_baz', StrConverters::toUnderscore('Foo Bar Baz'));
        $this->assertEquals('foo_bar_baz', convert_to_underscore('FooBarBaz'));
        $this->assertEquals('foo_bar_baz', convert_to_underscore('fooBarBaz'));
        $this->assertEquals('foo_bar_baz', StrConverters::toUnderscore('Foo_Bar_Baz'));

        $this->assertEquals('word_press', convert_to_underscore('word press'));
        $this->assertEquals('word_press', StrConverters::toUnderscore('wordPress'));
        $this->assertEquals('word_press', convert_to_underscore('WordPress'));
        $this->assertEquals('word_press', convert_to_underscore('Word-Press'));

        $this->assertEquals('convert_to_underscores', convert_to_underscore('convertToUnderscores'));
        $this->assertEquals('get_the_id', StrConverters::toUnderscore('getTheId'));
        $this->assertEquals('get_the_i_d', convert_to_underscore('getTheID'));
        $this->assertEquals('get_post_meta', StrConverters::toUnderscore('getPostMeta'));
    }

    public function testToUnderscoreIntentionalUnderscores()
    {
        $this->assertEquals('__return_empty_string', convert_to_underscore('__returnEmptyString'));
        $this->assertEquals('__foo_bar', convert_to_underscore('__fooBar'));
        $this->assertEquals('__return_false', StrConverters::toUnderscore('__returnFalse'));

        $this->assertEquals('_return_empty_string', convert_to_underscore('_returnEmptyString'));
        $this->assertEquals('_foo_bar__', convert_to_underscore('_fooBar__'));
        $this->assertEquals('esc_attr__', StrConverters::toUnderscore('escAttr__'));
    }
}
