<?php


namespace Check\App\Useful\Improver\Creator\HtmlElement;


use Check\App\Useful\Improver\Creator\HtmlClassParser;
use Check\App\Useful\Improver\Creator\HtmlScaleParser;
use Check\App\Useful\Improver\Creator\StyleHtmlParser;

class Div
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
    private $classes;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @var Div[]
     */
    private $children;

    /**
     * @var Div
     */
    private $parent;

    /**
     * @var array
     */
    private $minorStyles;

    /**
     * Div constructor.
     * @param string $cmd
     */
    public function __construct(string $cmd)
    {
        $this->cmd = $cmd;
    }

    /**
     * @param string $inputCommand
     * @return Div
     */
    public static function byString(string $inputCommand)
    {
        $self = new self($inputCommand);
        $self->_init();

        return $self;
    }

    protected function _init()
    {
        $this->styles = [];
        $this->children = [];
    }

    public function build()
    {
        $styleHtmlCreator = $this->_createdStyleHtmlCreator();
        $styleHtmlCreator->execute();
        $htmlClassCreator = $this->_createdHtmlClassCreator();
        $htmlClassCreator->execute();
        $htmlScaleCreator = $this->_createdHtmlScaleCreator();
        $htmlScaleCreator->execute();
        $this->styles = $styleHtmlCreator->getStyles();
        $this->classes = $htmlClassCreator->getClasses();
        $this->styles = array_merge($this->styles, $htmlScaleCreator->getStyles());
        $this->minorStyles = $styleHtmlCreator->getMinorStyles();
        $this->attributes = $htmlScaleCreator->getAttributes();
    }

    public function hasChildren()
    {
        return !empty($this->children);
    }

    /**
     * @param Div $div
     */
    public function addChild(Div $div)
    {
        $this->children[] = $div;
    }

    /**
     * @param Div $div
     */
    public function mapParent(Div $div)
    {
        $this->parent = $div;
    }

    /**
     * @return string
     */
    public function getCmd(): string
    {
        return $this->cmd;
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

    /**
     * @return array
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return Div
     */
    public function getParent(): Div
    {
        return $this->parent;
    }

    /**
     * @return Div[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return StyleHtmlParser
     */
    protected function _createdStyleHtmlCreator(): StyleHtmlParser
    {
        return StyleHtmlParser::byInputString($this->cmd);
    }

    /**
     * @return HtmlClassParser
     */
    protected function _createdHtmlClassCreator(): HtmlClassParser
    {
        return HtmlClassParser::byString($this->cmd);
    }

    /**
     * @return HtmlScaleParser
     */
    protected function _createdHtmlScaleCreator(): HtmlScaleParser
    {
        return HtmlScaleParser::byString($this->cmd);
    }
}