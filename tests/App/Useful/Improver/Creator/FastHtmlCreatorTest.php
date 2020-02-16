<?php

namespace App\Useful\Improver\Creator;

use Check\App\Useful\Improver\Creator\FastHtmlCreator;
use PHPUnit\Framework\TestCase;

class FastHtmlCreatorTest extends TestCase
{
    public function testExecute()
    {
        $text = <<<TXT
b
b
 a
  wa
  c
TXT;

        $inst = FastHtmlCreator::byText($text);
        $inst->execute();
        $dump = print_r($inst->getNestedMeta(), true);
        print_r(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' mit ' . __METHOD__ . PHP_EOL . '* $inst->getNestedMeta() *' . PHP_EOL . " = " . $dump . PHP_EOL);
        $dump = print_r($inst->getOutputHtml(), true);
        print_r(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' mit ' . __METHOD__ . PHP_EOL . '* $inst->getOutputHtml() *' . PHP_EOL . " = " . $dump . PHP_EOL);
    }
}