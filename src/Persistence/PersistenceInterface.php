<?php


namespace Check\Persistence;


interface PersistenceInterface
{
    /**
     * @param string $query
     * @param array $parameter
     * @return mixed
     */
    public function query(string $query, array $parameter);

    /**
     * @param string $query
     * @param array $parameter
     */
    public function execute(string $query, array $parameter): void;

    /**
     * @return int
     */
    public function getLastInsertedId(): int;
}