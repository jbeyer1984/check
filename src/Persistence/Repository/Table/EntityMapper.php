<?php


namespace Check\Persistence\Repository\Table;




class EntityMapper
{
    /**
     * @var array
     */
    private $keys;
    
    /**
     * @var array
     */
    private $fields;

    /**
     * EntityMapper constructor.
     * @param array $keys
     * @param array $fields
     */
    public function __construct(array $keys, array $fields)
    {
        $this->keys   = $keys;
        $this->fields = $fields;
    }


    public static function init(array $keys, array $fields)
    {
        return new self($keys, $fields);
    }

    /**
     * @return array
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    public function hasKeys(): bool
    {
        return !empty($this->keys);
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function getMap(): array
    {
        return array_merge($this->keys, $this->fields);
    }
}