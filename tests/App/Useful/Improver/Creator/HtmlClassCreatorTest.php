<?php

namespace App\Useful\Improver\Creator;

use Check\App\Useful\Improver\Creator\HtmlClassParser;
use PHPUnit\Framework\TestCase;

class HtmlClassCreatorTest extends TestCase
{
    public function testCanBeCreated()
    {
        $instance = HtmlClassParser::byString('wa*a*');
        $this->assertInstanceOf(HtmlClassParser::class, $instance);
    }

    public function testExecute()
    {
        $htmlClassParser = HtmlClassParser::byString('wa"a"(1920,1080)');
        $htmlClassParser->execute();
        $expected = [
            'a_wrapper',
            'autoscale',
        ];
        $this->assertEquals($expected, $htmlClassParser->getClasses());
    }
}
