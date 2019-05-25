<?php


namespace Check\Persistence\Condition\ConditionContainer;


use Check\Persistence\ConditionQueryInterface;

class ConditionContainer extends AbstractConditionQueryContainer implements ConditionQueryInterface
{
    private function __construct(string $concatString)
    {
        parent::__construct($concatString);
    }

    public static function And()
    {
        return new self('AND');
    }

    public static function Or()
    {
        return new self('OR');
    }
}