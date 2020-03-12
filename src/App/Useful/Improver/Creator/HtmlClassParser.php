<?php


namespace Check\App\Useful\Improver\Creator;


use Check\App\Utility\String\StringUtility;
use Exception;

class HtmlClassParser
{
    /**
     * @var string
     */
    private $cmd;

    /**
     * @var array
     */
    private $classes;

    /**
     * @var string
     */
    private $outputHtmlClassString;

    const SCALE_IDENTIFIER = '*';

    /**
     * HtmlClassCreator constructor.
     * @param string $cmd
     */
    protected function __construct(string $cmd)
    {
        $this->cmd = $cmd;
    }

    /**
     * @param string $cmd
     * @return HtmlClassParser
     */
    public static function byString(string $cmd)
    {
        $self = new self($cmd);
        $self->_init();

        return $self;
    }

    protected function _init()
    {
        $this->classes = [];
    }

    public function execute()
    {
        $cmd = $this->cmd;
        $firstPart = $this->_firstPart($cmd);
//        $dump = print_r($firstPart, true);
//        print_r(PHP_EOL . '-$- in ' . basename(__FILE__) . ':' . __LINE__ . ' mit ' . __METHOD__ . PHP_EOL . '* $firstPart *' . PHP_EOL . " = " . $dump . PHP_EOL);


        $this->_addIdentifierClasses($firstPart);

        $cmd = str_replace($firstPart, '', $cmd);

        $statement = $this->_extractClasses($cmd);
        if (!empty($statement)) {
            $cmd = str_replace($statement, '', $cmd);
        }

        $this->outputHtmlClassString = implode(' ', $this->classes);
    }

    /**
     * @param string $cmd
     * @return string
     */
    protected function _firstPart(string $cmd): string
    {
        $identifiers = [self::SCALE_IDENTIFIER, '"', ';', '(', ')', '[', ']'];
        $exploded = explode('@', str_replace($identifiers, '@', $cmd));
        $exploded = array_filter($exploded, function ($entry) {
            return !empty($entry);
        });
        $str = $cmd;
        if (!empty($exploded)) {
            $str = $exploded[0];
        }

        return $str;
    }

    /**
     * @return array
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * @return string
     */
    public function getOutputHtmlClassString(): string
    {
        return $this->outputHtmlClassString;
    }

    /**
     * @param string $firstPart
     * @throws Exception
     */
    protected function _addIdentifierClasses(string $firstPart)
    {
        $wrapper = '';
        if (0 === strpos($firstPart, 'w')) {
            $wrapper = '_wrapper';
            if (1 < strlen($firstPart)) {
                $firstPart = substr($firstPart, 1);
            }
        }
        if (!empty($wrapper) && empty($firstPart)) {
            throw new Exception("wrapper: w, should always have an identifier class, e.g: wb");
        }
        $this->classes[] = $firstPart . '_cl' . $wrapper;
    }

    /**
     * @param $cmd
     * @return string
     */
    protected function _extractClasses($cmd): string
    {
        $statement = StringUtility::extractBetweenIdentifier('"', $cmd);
        if (!empty($statement)) {
            $exploded = explode(',', $statement);
            foreach ($exploded as $class) {
                if ('auto' === $class) {
                    $this->classes[] = 'autoscale';
                } else {
                    $this->classes[] = $class;
                }

            }
        }

        return self::SCALE_IDENTIFIER . $statement . self::SCALE_IDENTIFIER;
    }
}