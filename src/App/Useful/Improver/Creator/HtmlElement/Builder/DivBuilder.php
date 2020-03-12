<?php


namespace Check\App\Useful\Improver\Creator\HtmlElement\Builder;


use Check\App\Useful\Improver\Creator\HtmlElement\Div;

class DivBuilder
{

    /**
     * DivBuilder constructor.
     */
    protected function __construct()
    {
    }

    public static function byString(string $inputCommand)
    {
        $div = Div::byString($inputCommand);
        $div->build();

        return $div;
    }
}