<?php


namespace Check\Persistence\Repository;


use Check\Persistence\ConditionQueryInterface;
use Check\Persistence\Repository\Table\Table;

interface BaseRepositoryInterface
{
    public function select(Table $table, ConditionQueryInterface $conditionContainer = null): array;
    public function save(Table $table, array $parameter): array;
//    public function insert(Table $table, array $map): void;
//    public function update(Table $table, array $map): void;
}