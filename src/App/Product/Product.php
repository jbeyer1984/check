<?php


namespace Check\App\Product;


class Product
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $created;

    /**
     * Product constructor.
     * @param int $id
     * @param string $name
     * @param string $description
     * @param string $created
     */
    public function __construct(int $id, string $name, string $description, string $created)
    {
        $this->id          = $id;
        $this->name        = $name;
        $this->description = $description;
        $this->created     = $created;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }
}