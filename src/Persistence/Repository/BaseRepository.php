<?php


namespace Check\Persistence\Repository;


use Check\Persistence\Condition\Condition;
use Check\Persistence\Condition\ConditionContainer\ConditionContainer;
use Check\Persistence\ConditionQueryInterface;
use Check\Persistence\PersistenceInterface;
use Check\Persistence\Repository\Table\Table;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var PersistenceInterface
     */
    private $persistence;

    /**
     * BaseRepository constructor.
     * @param PersistenceInterface $persistence
     */
    public function __construct(PersistenceInterface $persistence)
    {
        $this->persistence = $persistence;
    }

    /**
     * @param Table $table
     * @param ConditionQueryInterface $conditionContainer
     * @return array
     * @throws \Exception
     */
    public function select(Table $table, ConditionQueryInterface $conditionContainer = null): array
    {
        if (is_null($conditionContainer)) {
            $result = $this->selectAll($table);
        } else {
            $query = implode(
                PHP_EOL,
                [
                    $this->generateSelect($table),
                    $this->generateWhere($conditionContainer)
                ]
            );

            $parameter = $conditionContainer->getParameter();

            $result = $this->persistence->query($query, $parameter);    
        }
        
        return $result;
    }

    /**
     * @param Table $table
     * @return mixed
     * @throws \Exception
     */
    private function selectAll(Table $table)
    {
        $query = $this->generateSelect($table);

        $result = $this->persistence->query($query, []);

        return $result;
    }

    /**
     * @param Table $table
     * @param array $parameter
     * @return array
     * @throws \Exception
     */
    public function save(Table $table, array $parameter): array
    {
        $toInsert = $table->primaryHasInitValueByParameter($parameter); 
        if ($toInsert) {
            $parameter = $this->getOnlyFieldsParameter($table, $parameter);
            $parameter = $this->insert($table, $parameter);
        } else {
            if (!$table->hasPrimaryKey()) {
                throw new \Exception(sprintf('table %s should have primary key to update table data', $table->getName()));
            }
            $this->update($table, $parameter);
        }
        
        return $parameter;
    }

    private function getOnlyFieldsParameter(Table $table, array $parameter)
    {
        unset($parameter[$table->getPrimaryIdentifier()]);
        
        return $parameter;
    }

    /**
     * @param Table $table
     * @param array $parameter
     * @return array
     * @throws \Exception
     */
    public function insert(Table $table, array $parameter): array
    {
        $executeString = implode(
            PHP_EOL,
            [
                $this->generateInsert($table),
                $this->generateSets($parameter),
                ';',
                'SELECT LAST_INSERT_ID() as id;'
            ]
        );
        
        $this->persistence->execute($executeString, $parameter);
        $id = $this->persistence->getLastInsertedId(); /** @TODO implement transaction arround */
        
        
        $keys = [];
        $keys[$table->getPrimaryIdentifier()] = $id;
        
        $parameter = array_merge($keys, $parameter);
        
        return $parameter;

    }

    /**
     * @param Table $table
     * @param array $parameter
     * @return array
     * @throws \Exception
     */
    public function update(Table $table, array $parameter): array
    {
        $primaryIdentifier = $table->getPrimaryIdentifier();
        $whereCondition = ConditionContainer::And()
            ->add(Condition::operator($primaryIdentifier, '=', $parameter[$primaryIdentifier]))
        ;
        $executeString = implode(
            PHP_EOL,
            [
                $this->generateUpdate($table),
                $this->generateSets($parameter),
                $this->generateWhere($whereCondition)
            ]
        );
    
        $this->persistence->execute($executeString, $parameter);
        
        return $parameter;
    }

    /**
     * @param Table $table
     * @return string
     */
    private function generateSelect(Table $table): string
    {
        $tableName = $table->getName();
        $allFields = array_merge($table->getKeys(), $table->getFields());
        $fieldsImploded = implode(',', $allFields);
        $sql = <<<SQL
SELECT {$fieldsImploded} FROM {$tableName}
SQL;

        return $sql;
    }

    /**
     * @param ConditionQueryInterface $conditionContainer
     * @return string
     */
    private function generateWhere(ConditionQueryInterface $conditionContainer)
    {
        $conditionsImploded = $conditionContainer->getCondition();
        
        $where = <<<SQL
WHERE {$conditionsImploded}
SQL;

        return $where;
    }

    /**
     * @param Table $table
     * @return string
     */
    private function generateInsert(Table $table): string 
    {
        $tableName = $table->getName();
        $sql = <<<SQL
INSERT INTO {$tableName}
SQL;

        return $sql;
    }

    /**
     * @param array $parameter
     * @return string
     */
    private function generateSets(array $parameter): string
    {
        $sets = [];
        foreach ($parameter as $key => $value) {
            $sql = <<<SQL
{$key} = :{$key}
SQL;

            $sets[] = $sql;
        }
        
        $implodedString = 'SET ' . implode(',', $sets); 
        
        return $implodedString;
    }

    private function generateUpdate(Table $table)
    {
        $tableName = $table->getName();
        $sql = <<<SQL
UPDATE {$tableName}
SQL;

        return $sql;
    }
}