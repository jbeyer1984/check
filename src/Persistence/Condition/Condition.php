<?php


namespace Check\Persistence\Condition;



use Check\Persistence\ConditionInterface;

class Condition implements ConditionInterface
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $condition;

    /**
     * Condition constructor.
     * @param string $field
     * @param string $operator
     * @param mixed $value
     */
    private function __construct(string $field, string $operator, $value)
    {
        $this->field    = $field;
        $this->operator = $operator;
        $this->value    = $value;
    }

    /**
     * @param string $field
     * @param string $operator
     * @param $value
     * @return Condition
     * @throws \Exception
     */
    public static function operator(string $field, string $operator, $value)
    {
        $operatorsAccepted = ['=', '<', '>'];
        if (!in_array($operator, $operatorsAccepted)) {
            throw new \Exception(sprintf('operator is not supported, given %s', $operator));
        }
        $queryCondition = new self($field, $operator, $value);
        $queryCondition->writeOperator();
        
        return $queryCondition;
    }

    private function writeOperator()
    {
        $this->condition = $this->field . $this->operator . ':' . $this->field;
    }

    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}