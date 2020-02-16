<?php


namespace Check\App\Useful\Improver\Creator;


class StyleHtmlCreator
{
    /**
     * @var string
     */
    private $inputIdentifier;

    /**
     * @var array
     */
    private $styles;

    /**
     * @var string
     */
    private $outputStyleString;

    /**
     * StyleHtmlCreator constructor.
     * @param string $inputIdentifier
     */
    protected function __construct(string $inputIdentifier)
    {
        $this->inputIdentifier = $inputIdentifier;
    }

    public static function byInputString(string $inputIdentifier)
    {
        $self = new self ($inputIdentifier);
        $self->_init();

        return $self;
    }

    protected function _init()
    {
        $this->styles = [];
    }


    public function execute()
    {
        $fc = $this->inputIdentifier[0];
        switch ($fc) {
            case 'a':
                $this->styles[] = 'position: absolute';
                break;
        }

        $this->outputStyleString = implode('; ', $this->styles);
    }

    /**
     * @return string
     */
    public function getOutputStyleString(): string
    {
        return $this->outputStyleString;
    }
}