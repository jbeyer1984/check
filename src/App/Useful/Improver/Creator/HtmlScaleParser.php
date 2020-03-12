<?php


namespace Check\App\Useful\Improver\Creator;


use Check\App\Utility\String\StringUtility;
use Exception;

class HtmlScaleParser
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
    private $attributes;

    /**
     * ScaleHtmlCreator constructor.
     * @param string $cmd
     */
    protected function __construct(string $cmd)
    {
        $this->cmd = $cmd;
    }

    /**
     * @param string $cmd
     * @return HtmlScaleParser
     */
    public static function byString(string $cmd)
    {
        $self = new self($cmd);
        $self->_init();

        return $self;
    }

    protected function _init()
    {
        $this->styles = [];
        $this->attributes = [];
    }

    public function execute()
    {
        $cmd = $this->cmd;
        $this->_extractClasses($cmd);
    }

    /**
     * @param $cmd
     * @return string
     * @throws Exception
     */
    protected function _extractClasses($cmd): string
    {
        $identifier = '*';
        $statement = StringUtility::extractBetweenIdentifier($identifier, $cmd);
        if (!empty($statement)) {
            $exploded = explode(',', $statement);
            $dump = print_r($exploded, true);
            print_r(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' mit ' . __METHOD__ . PHP_EOL . '* $exploded *' . PHP_EOL . " = " . $dump . PHP_EOL);

            foreach ($exploded as $class) {
                if (false !== strpos($class, 'ff=')) {
                    $expl = explode('=', $class);
                    if (2 > count($expl)) {
                        throw new Exception('there should be one assignment for fs=');
                    }
                    $font = $expl[1];
                    $this->styles[] = 'font-family:' . $font;
                }
                if (false !== strpos($class, 'fs=')) {
                    $expl = explode('=', $class);
                    if (2 > count($expl)) {
                        throw new Exception('there should be one assignment for fs=');
                    }
                    $size = $expl[1];
                    $size = StringUtility::appendedOneTime($size, 'px');
                    $this->styles[] = 'font-size:' . $size;
                }
                if (false !== strpos($class, 'fsM=')) {
                    $expl = explode('=', $class);
                    if (2 > count($expl)) {
                        throw new Exception('there should be one assignment for fs=');
                    }
                    $height = $expl[1];
                    $heightPx = StringUtility::appendedOneTime($height, 'px');
                    $this->styles[] = 'font-size:' . $heightPx;
                    $this->attributes[] = 'max-height="' . $height . '"';
                    $this->attributes[] = 'max-fontsize="' . $height . '"';
                }
            }
        }

        return $identifier . $statement . $identifier;
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
    public function getAttributes()
    {
        return $this->attributes;
    }
}