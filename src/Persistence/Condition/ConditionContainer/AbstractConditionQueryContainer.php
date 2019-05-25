<?php


namespace Check\Persistence\Condition\ConditionContainer;


use Check\Persistence\ConditionDefinitionInterface;
use Check\Persistence\ConditionInterface;
use Check\Persistence\ConditionQueryInterface;

abstract class AbstractConditionQueryContainer implements ConditionQueryInterface
{
    /**
     * @var ConditionDefinitionInterface[]
     */
    private $data;

    /**
     * @var string
     */
    private $concatString = 'AND';

    /**
     * BaseConditionContainer constructor.
     * @param string $concatString
     */
    public function __construct(string $concatString)
    {
        $this->concatString = $concatString;
    }

    /**
     * @param ConditionInterface $queryCondition
     * @return $this
     */
    public function add(ConditionInterface $queryCondition)
    {
        $this->data[] = $queryCondition;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getCondition(): string
    {
        $conditions = [];
        foreach ($this->data as $queryCondition) {
            $conditions[] = $queryCondition->getCondition();
        }
        
        $conditionsString = implode(sprintf(' %s ', $this->concatString), $conditions);
        
//        $this->clearData();
        
        return $conditionsString;
    }

    /**
     * @return array
     */
    public function getParameter(): array
    {
        $keyValueArray = [];
        foreach($this->data as $queryCondition) {
            $keyValueArray[$queryCondition->getField()] = $queryCondition->getValue();
        }
        
        return $keyValueArray;
    }
//    private function clearData()
//    {
//        $this->data = [];
//    }
}