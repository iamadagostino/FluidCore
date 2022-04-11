<?php
declare(strict_types=1);

namespace Fluid\LiquidORM\DataMapper;

interface DataMapperInterface
{
    /**
     * Prepare the SQL query string
     *
     * @param string $query
     *
     * @return static
     */
    public function prepare(string $query) : static;

    /**
     * Explicit Data type for the parameter using the PDO::PARAM_* constants
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function bind(mixed $value) : mixed;

    /**
     * Method which combines both methods listed above, one of which optimized
     * for binding search querie, once the second argument $type is set
     *
     * @param array $fields
     * @param bool $isSearch
     *
     * @return self
     */
    public function bindParameters(array $fields, bool $isSearch = false) : self;

    /**
     * Returns the # of rows affected by a DELETE, INSERT or UPDATE statement
     *
     * @return int
     */
    public function rowsCount() : int;

    /**
     * Execute method which will execute the prepared method
     *
     * @return void
     */
    public function execute() : mixed;

    /**
     * Returns a single DB's row as an object
     *
     * @return object
     */
    public function result() : object;

    /**
     * Returns all the rows within the DB as an array
     *
     * @return array
     */
    public function results() : array;

    /**
     * Returns a DB's column
     *
     * @return mixed
     */
    // public function column() : mixed;

    /**
     * Returns the last inserted row ID from the DB's table
     *
     * @return int
     *
     * @throws Throwable
     */
    public function getLastID() : int;

    /**
     * Returns the query parameters and conditions
     *
     * @return mixed
     *
     * @throws Throwable
     */
    // public function buildQueryParameters() : mixed;
}
