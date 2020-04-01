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
fio-component
 wfio-logo;pos=a,ta=center;(100,200)[30,30]
  fio-logo(100,200)
  b;pos=a;
   wc"auto";pos=a,ta=center;(1920,1080)[20,20]*fsM=80,ff='Sans-Serif'*
    c
TXT;

        $htmlParser = FastHtmlParser::byText($text);
        $htmlParser->execute();
        $lessBuilder = LessBuilder::byDiv($htmlParser->getDiv());
        $lessBuilder->build();
        $dump = print_r($lessBuilder->getLess(), true);
        print_r(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' mit ' . __METHOD__ . PHP_EOL . '* $lessBuilder->getLess() *' . PHP_EOL . " = " . $dump . PHP_EOL);

    }
}
