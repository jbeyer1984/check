<?php


namespace Check\Persistence\Condition\ConditionContainer;


use Check\Persistence\Condition\ComplexCondition;
use Check\Persistence\Condition\Condition;
use Check\Persistence\ConditionDefinitionInterface;

class ConditionWrapper implements ConditionDefinitionInterface
{
    /**
     * @var ComplexCondition
     */
    private $condition;

    /**
     * ConditionWrapper constructor.
     * @param ComplexCondition $condition
     */
    private function __construct(ComplexCondition $condition)
    {
        $this->condition = $condition;
    }

    public static function wrapLower(Condition $condition)
    {
        $conditionField = $condition->getField();
        $operator = $condition->getOperator();
        $conditionString = <<<SQL
LOWER({$conditionField}) {$operator} :{$conditionField}
SQL;

        $wrapper = new self(ComplexCondition::String($conditionString, $condition->getField(), $condition->getValue()));
        
        return $wrapper;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->condition->getField();
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->condition->getValue();  
    }

    /**
     * @return mixed
     */
    public function getCondition()
    {
        return $this->condition->getCondition();
    }

}