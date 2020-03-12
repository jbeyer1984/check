<?php

namespace App\Useful\Improver\Creator\HtmlElement\Builder;

use Check\App\Useful\Improver\Creator\FastHtmlParser;
use Check\App\Useful\Improver\Creator\HtmlElement\Builder\LessBuilder;
use PHPUnit\Framework\TestCase;

class LessBuilderTest extends TestCase
{

    public function testBuild()
    {
        $text = <<<TXT
a
a[30,30]
 b;pos=a;
  wc"auto";pos=a,ta=center;(1920,1080)[20,20]*fsM=80,ff='Sans-Serif'*
   c
TXT;

        $htmlParser = FastHtmlParser::byText($text);
        $htmlParser->execute();
        $lessBuilder = LessBuilder::byDiv($htmlParser->getDiv());
        $lessBuilder->build();
    }
}
