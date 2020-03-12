<?php

namespace App\Useful\Improver\Creator;

use Check\App\Useful\Improver\Creator\FastHtmlParser;
use Check\App\Useful\Improver\Creator\HtmlElement\Builder\HtmlBuilder;
use PHPUnit\Framework\TestCase;

class FastHtmlParserTest extends TestCase
{
    public function testExecute()
    {
        $text = <<<TXT
a
a[30,30]
 b;a;
  wc"auto";pos=a,ta=center;(1920,1080)[20,20]*fsM=80,ff='Sans-Serif'*
   c
TXT;

        $htmlParser = FastHtmlParser::byText($text);
        $htmlParser->execute();

        $htmlBuilder = HtmlBuilder::byDiv($htmlParser->getDiv());
        $htmlBuilder->build();

//        $dump = print_r($inst->getNestedMeta(), true);
//        print_r(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' mit ' . __METHOD__ . PHP_EOL . '* $inst->getNestedMeta() *' . PHP_EOL . " = " . $dump . PHP_EOL);
//        $dump = print_r($inst->getOutputHtml(), true);
//        print_r(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' mit ' . __METHOD__ . PHP_EOL . '* $inst->getOutputHtml() *' . PHP_EOL . " = " . $dump . PHP_EOL);
    }
}