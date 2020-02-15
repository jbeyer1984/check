<?php


namespace Check\Persistence\Temporary;


use Check\Persistence\Repository\Table\EntityMapper;
use Check\Persistence\Repository\Table\GeneralMapper;
use Check\Persistence\Repository\Table\Table;
use Exception;

class TemporaryPersistence implements TemporaryPersistenceInterface
{
    /**
     * @var array
     */
    private $persistenceLookup = [];

    /**
     * @param Table $table
     * @param array $result
     * @throws Exception
     */
    public function update(Table $table, array $result): void
    {
        if (isset($result[0])) {
            foreach ($result as $row) {
                if (!is_array($row)) {
                    throw new Exception('$result must be type of array');
                }
                $this->update($table, $row);
            }
            return;
        }
        
        if (!$table->hasPrimaryKey()) {
            /** @TODO implement only fields */
            throw new Exception(sprintf('table %s has no primary key', $table->getName()));
        }
        
        $diffKeysNotFound = array_diff(
            array_values($table->getMap()),
            array_keys($result)
        );
        $allKeysFound = empty(
            $diffKeysNotFound
        );
        
        if (!$allKeysFound) {
            throw new Exception(
                sprintf(
                    'not all fields found in table=%s with keys=%s',
                    $table->getName(), implode(', ', $diffKeysNotFound)));
        }
        
        $incrementId = $this->generatedIncrementIdentifier($table, $result);
        
        $identifier = $this->generatedIdentifier($table, $incrementId);
        
        $generalMapper = GeneralMapper::init($result);
        if (!isset($this->persistenceLookup[$identifier])) {
            $this->persistenceLookup[$identifier] = [
                'old' => $generalMapper
            ];
            
            return;
        }
        
        if (!isset($this->persistenceLookup[$identifier]['new'])) {
            $this->persistenceLookup[$identifier]['new'] = $generalMapper;
            
            return;
        }

        $this->persistenceLookup[$identifier]['old'] = $this->persistenceLookup[$identifier]['new'];
        $this->persistenceLookup[$identifier]['new'] = $generalMapper;
    }

    /**
     * @param Table $table
     * @param array $result
     * @return bool
     * @throws Exception
     */
    public function hasUpdate(Table $table, array $result): bool
    {
        if (isset($result[0])) {
            if (1 < count($result)) {
                throw new Exception('update is only allowed for one entry');
            }
            $result = $result[0];
        }
        
        $incrementId = $this->generatedIncrementIdentifier($table, $result);
        $identifier = $this->generatedIdentifier($table, $incrementId);
        
        if (!isset($this->persistenceLookup[$identifier])) {
            return true;
        }
        
        if (!isset($this->persistenceLookup[$identifier]['old']) || !isset($this->persistenceLookup[$identifier]['new'])) {
            return true;
        }
        
        /** @var EntityMapper $old */
        $old = $this->persistenceLookup[$identifier]['old'];
        /** @var EntityMapper $new */
        $new = $this->persistenceLookup[$identifier]['new'];
        
        return !empty(array_diff(
            $new->getMap(),
            $old->getMap()
        ));
    }

    /**
     * @param Table $table
     * @param array $result
     * @return array
     * @throws Exception
     */
    public function getMapToUpdate(Table $table, array $result): array
    {
        if (isset($result[0])) {
            return []; /** @TODO implement getMapToUpdate for multiple */
        }
        
        $incrementId = $this->generatedIncrementIdentifier($table, $result);
        $identifier = $this->generatedIdentifier($table, $incrementId);
        if (!isset($this->persistenceLookup[$identifier]['old'])) {
            throw new Exception(sprintf('at least product should be updated by id = %s one time before', $incrementId));
        }
        
        $lastMap = $this->getLatestDeterminedMap($identifier);
        return array_diff(
            $result,
            $lastMap->getMap()
        );
        
    }

    /**
     * @param Table $table
     * @param array $result
     * @throws Exception
     */
    public function delete(Table $table, array $result): void
    {
        if (!$this->exists($table, $result)) {
            return;
        }

        $incrementId = $this->generatedIncrementIdentifier($table, $result);
        $identifier = $this->generatedIdentifier($table, $incrementId);

        unset($this->persistenceLookup[$identifier]);
    }

    /**
     * @param Table $table
     * @param array $result
     * @return bool
     * @throws Exception
     */
    public function exists(Table $table, array $result)
    {
        if (!isset($result[$table->getPrimaryIdentifier()])) {
            throw new Exception(sprintf('table %s does not find auto persistence primary key in result', $table->getName()));
        }

        $incrementId = $this->generatedIncrementIdentifier($table, $result);
        $identifier = $this->generatedIdentifier($table, $incrementId);

        return isset($this->persistenceLookup[$identifier]);
    }

    /**
     * @param Table $table
     * @param int $incrementId
     * @return string
     */
    private function generatedIdentifier(Table $table, int $incrementId): string
    {
        return $table->getName() . '_' . $incrementId;
    }

    /**
     * @param Table $table
     * @param array $result
     * @return string
     * @throws Exception
     */
    private function generatedIncrementIdentifier(Table $table, array $result): string
    {
        if (!isset($result[$table->getPrimaryIdentifier()])) {
            throw new Exception(sprintf('table %s does not find auto persistence primary key in result', $table->getName()));
        }
        
        return $result[$table->getPrimaryIdentifier()];
    }

    /**
     * @param string $identifier
     * @return EntityMapper
     */
    private function getLatestDeterminedMap(string $identifier)
    {
        if (!isset($this->persistenceLookup[$identifier]['new'])) {
            return $this->persistenceLookup[$identifier]['old'];
        }
        
        return $this->persistenceLookup[$identifier]['new'];
    }
}