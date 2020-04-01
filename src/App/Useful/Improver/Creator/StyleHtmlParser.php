<?php


namespace Check\App\Useful\Improver\Creator;


use Check\App\Utility\String\StringUtility;
use Exception;

class StyleHtmlParser
{
    /**
     * @var string
     */
    private $cmd;

    /**
     * @var array
     */
    private $styles;

    /**
     * @var array
     */
    private $minorStyles;

    /**
     * @var string
     */
    private $outputStyleString;

    const STYLE_IDENTIFIER =  ';';

    /**
     * StyleHtmlCreator constructor.
     * @param string $cmd
     */
    protected function __construct(string $cmd)
    {
        $this->cmd = $cmd;
    }

    public static function byInputString(string $cmd)
    {
        $self = new self ($cmd);
        $self->_init();

        return $self;
    }

    protected function _init()
    {
        $this->styles = [];
        $this->minorStyles = [];
    }


    public function execute()
    {
        $cmd = $this->cmd;
        $statement = $this->_extractStyle($cmd);
        if (!empty($statement)) {
            $cmd = str_replace($statement, '', $cmd);
        }

        $statement = $this->_extractBorderPx($cmd);
        if (!empty($statement)) {
            $cmd = str_replace($statement, '', $cmd);
        }

        $statement = $this->_extractMarginPx($cmd);
        if (!empty($statement)) {
            $cmd = str_replace($statement, '', $cmd);
        }


        $this->outputStyleString = implode('; ', $this->styles);
    }

    /**
     * @param string $cmd
     * @return string
     */
    protected function _extractStyle(string $cmd): string
    {
        $statement = StringUtility::extractBetweenIdentifier(self::STYLE_IDENTIFIER, $cmd);
        if (!empty($statement)) {
            $exploded = explode(',', $statement);
            foreach ($exploded as $style) {
                if (false !== strpos($style, 'pos=')) {
                    $expl = explode('=', $style);
                    if (2 > count($expl)) {
                        throw new Exception('there should be one assignment for pos=');
                    }
                    if ('a' == $expl[1]) {
                        $this->styles[] = 'position:absolute';
                    }
                } elseif (false !== strpos($style, 'ta=')) {
                    $expl = explode('=', $style);
                    if (2 > count($expl)) {
                        throw new Exception('there should be one assignment for ta=');
                    }
                    $definition = $expl[1];
                    $this->minorStyles[] = 'text-align: ' . $definition;
                } else {
                    $this->minorStyles[] = $style;
                }
            }
        }

        return self::STYLE_IDENTIFIER . $statement . self::STYLE_IDENTIFIER;
    }

    /**
     * @param string $cmd
     * @return string
     * @throws Exception
     */
    protected function _extractBorderPx(string $cmd): string
    {
        $statement = StringUtility::extractBetweenIdentifierPair('(', ')', $cmd);
        if (!empty($statement)) {
            $exploded = explode(',', $statement);
            if (2 > count($exploded)) {
                throw new Exception('there should be at least 2 parameters between pair: e.g' . '(1920,1080)');
            }
            $width = $exploded[0];
            $height = $exploded[1];
            $width = StringUtility::appendedOneTime($width, 'px');
            $height = StringUtility::appendedOneTime($height, 'px');
            $this->styles[] = 'width: ' . $width;
            $this->styles[] = 'height: ' . $height;
        }

        return '(' . $statement . ')';
    }

    /**
     * @param string $cmd
     * @return string
     * @throws Exception
     */
    protected function _extractMarginPx(string $cmd)
    {
        $statement = StringUtility::extractBetweenIdentifierPair('[', ']', $cmd);
        if (!empty($statement)) {
            $exploded = explode(',', $statement);
            if (2 > count($exploded)) {
                throw new Exception('there should be at least 2 parameters between pair: e.g' . '(1920,1080)');
            }
            $marginLeft = $exploded[0];
            $marginTop = $exploded[1];
            $marginLeft = StringUtility::appendedOneTime($marginLeft, 'px');
            $marginTop = StringUtility::appendedOneTime($marginTop, 'px');
            $this->styles[] = 'margin-left: ' . $marginLeft;
            $this->styles[] = 'margin-top: ' . $marginTop;
        }

        return '[' . $statement . ']';
    }

    /**
     * @return array
     */
    public function getStyles(): array
    {
        return $this->styles;
    }

    /**
     * @return array
     */
    public function getMinorStyles(): array
    {
        return $this->minorStyles;
    }
}