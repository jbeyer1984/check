<?php


namespace Check\App\Useful\Improver\Creator\HtmlElement\Builder;


use Check\App\Useful\Improver\Creator\HtmlElement\Div;

class LessBuilder
{
    /**
     * @var Div
     */
    private $div;

    const INDENT = '    ';

    /**
     * LessBuilder constructor.
     * @param Div $div
     */
    protected function __construct(Div $div)
    {
        $this->div = $div;
    }

    /**
     * @param Div $div
     * @return LessBuilder
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
        $less = $this->_builtLess($this->div, 0);
        $dump = print_r($less, true);
        print_r(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' mit ' . __METHOD__ . PHP_EOL . '* $html *' . PHP_EOL . " = " . $dump . PHP_EOL);
    }


    /**
     * @param Div $div
     * @param int $indent
     * @return string
     */
    protected function _builtLess(Div $div, int $indent)
    {
        $less = '';
        foreach ($div->getChildren() as $div) {
            if (!$div->hasChildren()) {
                $less .= PHP_EOL;
                $less .= $this->_lessBlock($div, $indent);
            } else {
                $less .= PHP_EOL;
                $less .= $this->lessBlockHead($div, $indent);
                $less .= PHP_EOL;
                $styles = array_merge($div->getStyles(), $div->getMinorStyles());
                if (!empty($styles)) {
                    $indentString = str_repeat(self::INDENT, $indent+1);
                    $styleBlock = implode(PHP_EOL . $indentString, $styles);
                    $less .= $indentString . $styleBlock;
                }
                $less .= $this->_builtLess($div, $indent+1);
                $less .= PHP_EOL;
                $less .= $this->lessBlockFoot($indent);
            }
        }

        return $less;
    }


    /**
     * @param Div $div
     * @param int $indent
     * @return string
     */
    protected function _lessBlock(Div $div, int $indent)
    {
        $indentStringPlusOne = str_repeat(self::INDENT, $indent+1);
        $less = $this->lessBlockHead($div, $indent);
        $less .= PHP_EOL;
        $less .= $indentStringPlusOne;
        $less .= implode(PHP_EOL . $indentStringPlusOne, array_merge($div->getStyles(), $div->getMinorStyles()));
        $less .= PHP_EOL;
        $less .= $this->lessBlockFoot($indent);
        return  $less;
    }

    /**
     * @param Div $div
     * @param int $indent
     * @return string
     */
    private function lessBlockHead(Div $div, int $indent)
    {
        $indentString = str_repeat(self::INDENT, $indent);
        $classes = $div->getClasses();
        $class = array_shift($classes);

        return $indentString . '.' . $class . '{';
    }

    /**
     * @param int $indent
     * @return string
     */
    protected function lessBlockFoot(int $indent)
    {
        $indentString = str_repeat(self::INDENT, $indent);

        return $indentString . '}';
    }
}