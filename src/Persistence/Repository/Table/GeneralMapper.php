<?php


namespace Check\Persistence\Repository\Table;


class GeneralMapper
{
    /**
     * @var array
     */
    private $map;

    /**
     * GeneralMapper constructor.
     * @param array $map
     */
    private function __construct(array $map)
    {
        $this->map = $map;
    }

    /**
     * @param array $map
     * @return GeneralMapper
     */
    public static function init(array $map)
    {
        return new self($map);
    }

    /**
     * @return array
     */
    public function getMap(): array
    {
        return $this->map;
    }
}