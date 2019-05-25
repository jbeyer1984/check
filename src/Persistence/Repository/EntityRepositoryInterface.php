<?php


namespace Check\Persistence\Repository;


use Check\Persistence\ConditionQueryInterface;

interface EntityRepositoryInterface
{
    public function findById(int $id);
    public function findBy(ConditionQueryInterface $conditionContainer): array;
}