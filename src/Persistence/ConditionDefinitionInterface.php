<?php


namespace Check\Persistence;


interface ConditionDefinitionInterface extends ConditionInterface
{
    /**
     * @return string
     */
    public function getField(): string;

    /**
     * @return mixed
     */
    public function getValue();
}