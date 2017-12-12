<?php

namespace Fulcrum\Tests\Extender\Str;

use Fulcrum\Tests\Unit\UnitTestCase;

class TruncateTest extends UnitTestCase
{
    public function testByCharacters()
    {
        $string = 'Fulcrum is disrupting the way you build custom WordPress websites.';
        $this->assertEquals('Fulcrum is...', truncate_by_characters($string, 10));
        $this->assertEquals('Fulcrum i...', truncate_by_characters($string, 9));

        $this->assertEquals('这是一...', truncate_by_characters('这是一段中文', 6));
    }

    public function testByCharactersEndingCharacter()
    {
        $string = 'Fulcrum is disrupting the way you build custom WordPress websites.';

        $this->assertEquals('Fulcrum is', truncate_by_characters($string, 10, ''));
        $this->assertEquals('Fulcrum i [...]', truncate_by_characters($string, 9, ' [...]'));

        $this->assertEquals('这是一 [..] ', truncate_by_characters('这是一段中文', 6, ' [..] '));
    }

    public function testByCharactersNone()
    {
        $this->assertEquals('Tonya Mork', truncate_by_characters('Tonya Mork', 50));

        $string = 'Fulcrum is disrupting the way you build custom WordPress websites.';
        $this->assertEquals($string, truncate_by_characters($string, 1000));
    }

    public function testByWords()
    {
        $string = 'Fulcrum is disrupting the way you build custom WordPress websites.';
        $this->assertEquals('Fulcrum is disrupting...', truncate_by_words($string, 3));
        $this->assertEquals('Fulcrum is...', truncate_by_words($string, 2));

        $string = "Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός";
        $this->assertEquals('Τάχιστη αλώπηξ βαφής...', truncate_by_words($string, 3));
    }

    public function testByWordsEndingCharacter()
    {
        $string = 'Fulcrum is disrupting the way you build custom WordPress websites.';

        $this->assertEquals('Fulcrum is', truncate_by_words($string, 2, ''));
        $this->assertEquals('Fulcrum is disrupting the way [...]', truncate_by_words($string, 5, ' [...]'));

        $string = "Τάχιστη αλώπηξ βαφής ψημένη γη, δρασκελίζει υπέρ νωθρού κυνός";
        $this->assertEquals('Τάχιστη αλώπηξ βαφής ψημένη __', truncate_by_words($string, 4, ' __'));
    }

    public function testByWordsNone()
    {
        $this->assertEquals('Tonya Mork', truncate_by_words('Tonya Mork', 3));

        $string = 'Fulcrum is disrupting the way you build custom WordPress websites.';
        $this->assertEquals($string, truncate_by_words($string, 10));
    }
}
