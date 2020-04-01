<?php


namespace Check\App\Useful\Improver\Creator\HtmlElement\Builder;


use Check\App\Useful\Improver\Creator\HtmlElement\Div;

class HtmlBuilder
{
    /**
     * @var Div
     */
    private $div;

    const INDENT = '    ';

    /**
     * HtmlBuilder constructor.
     * @param Div $div
     */
    protected function __construct(Div $div)
    {
        $this->div = $div;
    }

    /**
     * @param Div $div
     * @return HtmlBuilder
     */
    public static function byDiv(Div $div)
    {
        $self = new self($div);
        $self->_init();

        return $self;
    }

    protected function _init()
    {

    }


    public function build()
    {
        $html = $this->_builtHtml($this->div, 0);
        $dump = print_r($html, true);
        print_r(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' mit ' . __METHOD__ . PHP_EOL . '* $html *' . PHP_EOL . " = " . $dump . PHP_EOL);
    }


    /**
     * @param Div $div
     * @param int $indent
     * @return string
     */
    protected function _builtHtml(Div $div, int $indent)
    {
        $html = '';
        foreach ($div->getChildren() as $div) {
            if (!$div->hasChildren()) {
                $html .= PHP_EOL;
                $html .= $this->_divLine($div, $indent);
            } else {
                $html .= PHP_EOL;
                $html .= $this->_divHead($div, $indent);
                $html .= $this->_builtHtml($div, $indent+1);
                $html .= PHP_EOL;
                $html .= $this->_divFoot($indent);
            }
        }

        return $html;
    }


    /**
     * @param Div $div
     * @param int $indent
     * @return string
     */
    protected function _divLine(Div $div, int $indent)
    {
        return $this->_divHead($div, $indent) . $this->_divFoot(0);
    }

    /**
     * @param Div $div
     * @param int $indent
     * @return string
     */
    private function _divHead(Div $div, int $indent)
    {
        $indentString = str_repeat(self::INDENT, $indent);
        $classString = '';
        if (!empty($div->getClasses())) {
            $classString .= ' class="' . implode(' ', $div->getClasses()) . '"';
        }
        $attributesString = '';
        if (!empty($div->getAttributes())) {
            $attributesString .=  ' ' . implode(' ', $div->getAttributes());
        }

        return <<<HTML
{$indentString}<div{$classString}{$attributesString}>
HTML;
    }

    /**
     * @param int $indent
     * @return string
     */
    protected function _divFoot(int $indent)
    {
        $indentString = str_repeat(self::INDENT, $indent);
        return <<<HTML
$indentString</div>
HTML;
    }
}