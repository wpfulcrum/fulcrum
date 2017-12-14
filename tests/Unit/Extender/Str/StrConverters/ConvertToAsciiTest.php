<?php

namespace Fulcrum\Tests\Unit\Extender\Str;

use Fulcrum\Tests\Unit\UnitTestCase;
use Fulcrum\Extender\Str\StrConverters;

class ConvertToAsciiTest extends UnitTestCase
{
    public function testToAsciiEmpties()
    {
        $this->assertEquals('  ', convert_to_ascii('  '));
        $this->assertEquals('           ', convert_to_ascii('           '));
        $this->assertEquals('', convert_to_ascii(''));
    }

    public function testToAscii()
    {

        foreach ($this->getAscii() as $dataSet) {
            $this->assertEquals($dataSet[0], convert_to_ascii($dataSet[1]));
            $this->assertEquals($dataSet[0], StrConverters::toAscii($dataSet[1]));
        }
    }

    protected function getAscii()
    {
        return [
            ['foo bar', 'fòô bàř'],
            [' TEST ', ' ŤÉŚŢ '],
            ['f = z = 3', 'φ = ź = 3'],
            ['perevirka', 'перевірка'],
            ['lysaya gora', 'лысая гора'],
            ['shchuka', 'щука'],
            ['', '漢字'],
            ['xin chao the gioi', 'xin chào thế giới'],
            ['XIN CHAO THE GIOI', 'XIN CHÀO THẾ GIỚI'],
            ['dam phat chet luon', 'đấm phát chết luôn'],
            ['aeoeueAEOEUE', 'äöüÄÖÜ'],
        ];
    }
}
