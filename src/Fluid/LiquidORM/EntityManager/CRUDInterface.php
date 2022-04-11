<?php

declare(strict_types=1);

namespace Fluid\LiquidORM\EntityManager;

interface CRUDInterface
{
    /**
     * Returns the storage schema's name as string
     *
     * @return string
     */
    public function getSchema() : string;

    /**
     * Returns the primary key for the storage's schema
     *
     * @return string
     */
    public function getSchemaID() : string;

    /**
     * Returns the l;ast inserted ID
     *
     * @return int
     */
    public function lastID() : int;

    /**
     * Create method which inserts data within a storage table
     *
     * @param array $fields
     *
     * @return bool
     */
    public function create(array $fields = []) : bool;

    /**
     * Returns an array of DB's rows based on the individual supplied arguments
     *
     * @param array $selectors = []
     * @param array $paramenters = []
     * @param array $conditions = []
     * @param array $optional = []
     *
     * @return array
     */
    public function read(array $selectors = [], array $parameters = [], array $conditions = [], array $optional = []) : array;

    /**
     * Update method which update 1 or more rows of data within the storage's table
     *
     * @param string $pk
     * @param array $fields = []
     *
     * @return bool
     */
    public function update(string $pk, array $fields = []) : bool;

    /**
     * Delete method which will permanently delete a row from the storage table
     *
     * @param array $conditions = []
     *
     * @return bool
     */
    public function delete(array $conditions = []) : bool;

    /**
     * Search method which returns queried search results
     *
     * @param array $selectors
     * @param array $conditions = []
     *
     * @return null|array
     */
    public function search(array $selectors, array $conditions = []) : ?array;

    /**
     * Returns a custom query string.
     * The second argument can assign and associate an array of conditions for
     * the query string
     *
     * @param string $rawQUery
     * @param array|null $conditions
     *
     * @return mixed
     */
    public function rawQuery(string $rawQuery, ?array $conditions = []) : mixed;
}
