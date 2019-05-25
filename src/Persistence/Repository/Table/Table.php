<?php


namespace Check\Persistence\Repository\Table;


class Table
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $hasPrimaryKey;

    /**
     * @var EntityMapper
     */
    private $entityMapper;

    /**
     * Table constructor.
     * @param string $name
     * @param array $keys
     * @param array $fields
     */
    private function __construct(string $name, array $keys, array $fields)
    {
        $this->name         = $name;
        $this->entityMapper = EntityMapper::init($keys, $fields);
        $this->hasPrimaryKey = $this->entityMapper->hasKeys();
    }

    /**
     * @param string $name
     * @param array $keys
     * @param array $fields
     * @return Table
     */
    public static function withPrimaryKey(string $name, array $keys, array $fields)
    {
        $table = new self($name, $keys, $fields);
        $table->hasPrimaryKey = true;
        
        return $table;
    }

    /**
     * @param string $name
     * @param array $fields
     * @return Table
     */
    public static function onlyFields(string $name, array $fields)
    {
        $table = new self($name, [], $fields);
        $table->hasPrimaryKey = false;

        return $table;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getKeys(): array
    {
        return $this->entityMapper->getKeys();
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->entityMapper->getFields();
    }

    /**
     * @return array
     */
    public function getMap(): array
    {
        return $this->entityMapper->getMap();
    }

    /**
     * @return bool
     */
    public function hasPrimaryKey(): bool
    {
        return $this->hasPrimaryKey;
    }

    /**
     * @return string
     */
    public function getPrimaryIdentifier(): string
    {
        return implode('', $this->getKeys());
    }

    /**
     * @param array $parameter
     * @return bool
     */
    public function primaryHasInitValueByParameter(array $parameter): bool
    {
        return empty($parameter[$this->getPrimaryIdentifier()]);
    }
}