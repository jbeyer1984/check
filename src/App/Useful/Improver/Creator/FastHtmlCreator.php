<?php


namespace Check\App\Useful\Improver\Creator;


class FastHtmlCreator
{
    const INDENT = '    ';
    /**
     * @var string
     */
    private $text;

    /**
     * @var array
     */
    private $nestedMeta;

    /**
     * @var string
     */
    private $outputHtml;

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
     * @return FastHtmlCreator
     */
    public static function byText(string $text)
    {
        $self = new self($text);
        $self->_init();

        return $self;
    }

    protected function _init()
    {
        $this->nestedMeta = [];
    }

    public function execute()
    {
        $lines = explode(PHP_EOL, $this->text);
        $inputCharacterPosition = 0;
        $this->nestedMeta = $this->_recFill([], $lines, '', $inputCharacterPosition);
        $html = $this->_createdHtml($this->nestedMeta, 0);
        $this->outputHtml = $html;
    }

    /**
     * @return string
     */
    public function getOutputHtml(): string
    {
        return $this->outputHtml;
    }

    /**
     * @param array $nestedMeta
     * @param array $lines
     * @param string $parent
     * @param int $inputCharacterPosition
     * @return array
     */
    protected function _recFill(array $nestedMeta, array &$lines, string $parent, int $inputCharacterPosition)
    {
        $line = array_shift($lines);
        $nextLine = '';
        foreach ($lines as $line) {
            $nextLine = $line;
            break;
        }
        $aheadNestedChange = false;
        preg_match('/\S/', $nextLine, $match);
        if (!empty($match)) {
            $firstCharacterPositionTemp = strpos($nextLine, $match[0]);
            $cmd = substr($line, $firstCharacterPositionTemp);
            if ($inputCharacterPosition < $firstCharacterPositionTemp && empty($lines)) {
                $aheadNestedChange = true;
                $nestedMeta[] = ['parent' => $parent, $cmd];
            } elseif ($inputCharacterPosition < $firstCharacterPositionTemp) {
                $aheadNestedChange = true;
                $nestedMeta[] = array_merge(
                    ['parent' => $parent, $cmd],
                    $this->_recFill([], $lines, $cmd, $firstCharacterPositionTemp)
                );
            } elseif ($inputCharacterPosition > $firstCharacterPositionTemp) {
                $aheadNestedChange = true;
                array_unshift($lines, $nextLine);
            }
        }
        if ($aheadNestedChange) {
            return $nestedMeta;
        }
        if ('' === $nextLine) {
            return $nestedMeta;
        }

        preg_match('/\S/', $line, $match);
        if (!empty($match)) {
            $firstCharacterPositionTemp = strpos($line, $match[0]);
            $cmd = substr($line, $firstCharacterPositionTemp);
            if ($inputCharacterPosition === $firstCharacterPositionTemp) {
                $nestedMeta[] = $cmd;

                $nestedMeta = $this->_recFill($nestedMeta, $lines, $cmd, $firstCharacterPositionTemp);
            }/* elseif ($inputCharacterPosition < $firstCharacterPositionTemp && empty($lines)) {
                $nestedMeta[] = ['parent' => $parent, $cmd];
            } elseif ($inputCharacterPosition < $firstCharacterPositionTemp) {
                $nestedMeta[] = array_merge(
                    ['parent' => $parent, $cmd],
                    $this->_recFill([], $lines, $cmd, $firstCharacterPositionTemp)
                );
            } elseif ($inputCharacterPosition > $firstCharacterPositionTemp) {
                array_unshift($lines, $line);
            }*/
        }

        return $nestedMeta;
    }

    /**
     * @param array $nestedArray
     * @param string $html
     * @param int $indent
     * @return string
     */
    protected function _createdHtml(array $nestedArray, int $indent)
    {
        $html = '';
        foreach ($nestedArray as $entry) {
            if (!is_array($entry)) {
                $div = $this->_div($entry, $indent);
                $html .= PHP_EOL . $div;
            } else {
                $identifier = $entry['parent'];
                unset($entry['parent']);
                $html .= PHP_EOL;
                $html .= $this->_divHead($identifier, $indent);
                $html .= $this->_createdHtml($entry, $indent+1);
                $html .= PHP_EOL;
                $html .= $this->_divFoot($indent);
            }
        }

        return $html;
    }

    /**
     * @param string $identifier
     * @param int $indent
     * @return string
     */
    protected function _div(string $identifier, int $indent)
    {
        $between = '';
        $subIdentifier = '';
        if (0 === strpos($identifier, 'w')) {
//            $styles[] = 'position: absolute';
            $subIdentifier = substr($identifier, 1);
            $between .= PHP_EOL;
            $between .= $this->_divLine($subIdentifier, $indent+1);
            $between .= PHP_EOL;
        }
        if ('' === $between) {
            return $this->_divLine($identifier, $indent);
        }
//        if (!empty($subIdentifier)) {
//            $identifier = $subIdentifier;
//        }
        return $this->_divHead($identifier, $indent) . $between. $this->_divFoot($indent);
    }

    /**
     * @param string $identifier
     * @param int $indent
     * @return string
     */
    protected function _divLine(string $identifier, int $indent)
    {
        return $this->_divHead($identifier, $indent) . $this->_divFoot(0);
    }

    /**
     * @param string $identifier
     * @param int $indent
     * @return string
     */
    private function _divHead(string $identifier, int $indent)
    {
        $indentString = str_repeat(self::INDENT, $indent);
        $styleHtmlCreator = StyleHtmlCreator::byInputString($identifier);
        $styleHtmlCreator->execute();
        $styleString = $styleHtmlCreator->getOutputStyleString();
        if (empty($styleString)) {
            return <<<HTML
$indentString<div class="$identifier">
HTML;
        }

        return <<<HTML
$indentString<div class="$identifier" style="$styleString">
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

    /**
     * @return array
     */
    public function getNestedMeta(): array
    {
        return $this->nestedMeta;
    }
}

