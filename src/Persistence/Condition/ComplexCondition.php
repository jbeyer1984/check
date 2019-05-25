<?php


namespace Check\Persistence\Condition;


use Check\Persistence\ConditionDefinitionInterface;

class ComplexCondition implements ConditionDefinitionInterface
{
    /**
     * @var string
     */
    private $condition;

    /**
     * @var string
     */
    private $field;
    
    /**
     * @var mixed
     */
    private $value;

    /**
     * ComplexCondition constructor.
     * @param string $condition
     * @param string $field
     * @param mixed $value
     */
    private function __construct(string $condition, string $field, $value)
    {
        $this->condition = $condition;
        $this->field     = $field;
        $this->value     = $value;
    }

    /**
     * @param string $condition
     * @param string $field
     * @param mixed $value
     * @return ComplexCondition
     */
    public static function String(string $condition, string $field, $value)
    {
        return new self($condition, $field, $value);
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }
}