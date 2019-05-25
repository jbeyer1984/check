<?php


namespace Check\Persistence\Temporary;


use Check\Persistence\Repository\Table\Table;

interface TemporaryPersistenceInterface
{
    public function update(Table $table, array $result): void;
    public function hasUpdate(Table $table, array $result): bool;
    public function getMapToUpdate(Table $table, array $result): array;
}