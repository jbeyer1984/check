<?php


namespace Check\App\Useful\Improver\Creator;


use Check\App\Useful\Improver\Creator\HtmlElement\Builder\DivBuilder;
use Check\App\Useful\Improver\Creator\HtmlElement\Div;

class FastHtmlParser
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var Div
     */
    private $div;

    /**
     * @var array
     */
    private $divIdentifiers;

    const INDENT = '    ';

    /**
     * FastHtmlCreator constructor.
     * @param string $inputText
     */
    public function __construct(string $inputText)
    {
        $this->text = $inputText;
    }

    /**
     * @param string $text
     * @return FastHtmlParser
     */
    public static function byText(string $text)
    {
        $self = new self($text);
        $self->_init();

        return $self;
    }

    protected function _init()
    {
    }

    public function execute()
    {
        $lines = explode(PHP_EOL, $this->text);
        $inputCharacterPosition = 0;
        $this->div = DivBuilder::byString('root');
        $this->_recFill($this->div, $lines, $inputCharacterPosition);
        $this->_traverseToDivIdentifiers($this->div, 0);
//        $dump = print_r($this->divIdentifiers, true);
//        print_r(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' mit ' . __METHOD__ . PHP_EOL . '* $this->divIdentifiers *' . PHP_EOL . " = " . $dump . PHP_EOL);
    }

    /**
     * @return string
     */
    public function getOutputHtml(): string
    {
        return $this->outputHtml;
    }

    /**
     * @param Div $div
     * @param array $lines
     * @param int $inputCharacterPosition
     */
    protected function _recFill(Div $div, array &$lines, int $inputCharacterPosition)
    {
        $line = array_shift($lines);
        preg_match('/\S/', $line, $match);
        if (!empty($match)) {
            $firstCharacterPositionTemp = strpos($line, $match[0]);
            $cmd = substr($line, $firstCharacterPositionTemp);
            if ($inputCharacterPosition === $firstCharacterPositionTemp) {
                $cDiv = DivBuilder::byString($cmd);
                $cDiv->mapParent($div);
                $div->addChild($cDiv);
                $this->_recFill($div, $lines, $firstCharacterPositionTemp);
                return;
            } elseif ($inputCharacterPosition < $firstCharacterPositionTemp && empty($lines)) {
                $cDiv = DivBuilder::byString($cmd);
                $cDiv->mapParent($div);
                $div->addChild($cDiv);
                $this->_recFill($cDiv, $lines, $firstCharacterPositionTemp);
                return;
            } elseif ($inputCharacterPosition < $firstCharacterPositionTemp) {
                $cDiv = DivBuilder::byString($cmd);
                $cDiv->mapParent($div);
                $div->addChild($cDiv);
                $this->_recFill($cDiv, $lines, $firstCharacterPositionTemp);
                return;
            } elseif ($inputCharacterPosition > $firstCharacterPositionTemp) {
                $cDiv = DivBuilder::byString($cmd);
                $div = $div->getParent();
                $div->addChild($cDiv);
                $this->_recFill($div, $lines, $firstCharacterPositionTemp);
                return;
            }
        }
    }

    /**
     * @param Div $div
     * @param int $indent
     */
    protected function _traverseToDivIdentifiers(Div $div, int $indent)
    {
        $indentString = str_repeat(self::INDENT, $indent);
        foreach ($div->getChildren() as $div) {
            $this->divIdentifiers[] = $indentString . $div->getCmd();
            if ($div->hasChildren()) {
                $this->_traverseToDivIdentifiers($div, $indent+1);
            }
        }
    }

    /**
     * @return Div
     */
    public function getDiv(): Div
    {
        return $this->div;
    }
}

