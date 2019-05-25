<?php


namespace Check\Persistence\Repository\Decorator;


use Check\Persistence\ConditionQueryInterface;
use Check\Persistence\Repository\BaseRepositoryInterface;
use Check\Persistence\Repository\Table\Table;
use Check\Persistence\Temporary\TemporaryPersistenceInterface;

class RepositoryTemporaryPersistenceDecorator implements BaseRepositoryInterface
{
    /**
     * @var BaseRepositoryInterface
     */
    private $repository;
    
    /**
     * @var TemporaryPersistenceInterface
     */
    private $temporaryPersistence;

    /**
     * RepositoryTemporaryPersistenceDecorator constructor.
     * @param BaseRepositoryInterface $repository
     * @param TemporaryPersistenceInterface $temporaryPersistence
     */
    public function __construct(BaseRepositoryInterface $repository, TemporaryPersistenceInterface $temporaryPersistence
    ) {
        $this->repository           = $repository;
        $this->temporaryPersistence = $temporaryPersistence;
    }

    /**
     * @param Table $table
     * @param ConditionQueryInterface|null $conditionContainer
     * @return array
     */
    public function select(Table $table, ConditionQueryInterface $conditionContainer = null): array
    {
        $result = $this->repository->select($table, $conditionContainer);
        if (empty($result)) {
            
            return $result;
        }
        if ($this->temporaryPersistence->hasUpdate($table, $result)) {
            $this->temporaryPersistence->update($table, $result);
        }
        
        return $result;
    }

    /**
     * @param Table $table
     * @param array $parameter
     * @return array
     */
    public function save(Table $table, array $parameter): array
    {
        $toInsert = $table->primaryHasInitValueByParameter($parameter);

        if ($toInsert) {
            $parameter = $this->repository->save($table, $parameter);
            $this->temporaryPersistence->update($table, $parameter);
        } else { // update
            $primaryIdentifier = $table->getPrimaryIdentifier();

            $parameterIdMap = [$primaryIdentifier => $parameter[$primaryIdentifier]];
            $parameterMap = $this->temporaryPersistence->getMapToUpdate($table, $parameter);

            $parameterForDb = array_merge(
                $parameterIdMap,
                $parameterMap
            );
            
            if ($this->temporaryPersistence->hasUpdate($table, $parameter)) {
                $this->repository->save($table, $parameterForDb);
                $this->temporaryPersistence->update($table, $parameter);
            }
        }
        
        return $parameter;
    }
}