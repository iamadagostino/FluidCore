<?php

declare(strict_types=1);

namespace Fluid\LiquidORM\DataRepository;

interface DataRepositoryInterface
{
    /**
     * Find and return an item by its ID.
     *
     * @param integer $id
     * @return array
     */
    public function find(int $id) : array;

    /**
     * Find and return all table rows as an array.
     *
     * @return array
     */
    public function findAll() : array;

    /**
     * Find and return 1 or more rows by various argument which are
     * optional by default.
     *
     * @param array $selectors = []
     * @param array $conditions =[]
     * @param array $parameters =[]
     * @param array $optional =[]
     * @return array
     */
    public function findBy(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []) : array;

    /**
     * Find and return 1 row by the method's arguement.
     *
     * @param array $conditions
     * @return array
     */
    public function findOneBy(array $conditions) : array;

    /**
     * Returns a single row from the Storage's table as an object.
     *
     * @param array $conditions = []
     * @param array $selectors = []
     * @return object
     */
    public function findObjectBy(array $conditions = [], array $selectors = []) : object;

    /**
     * Returns the search results based on the user Search's conditions
     * and parameters.
     *
     * @param array $selectors = []
     * @param array $conditions = []
     * @param array $parameters = []
     * @param array $optional = []
     * @return array
     * @throws DataRepositoryInvalidArgumentException
     */
    public function findBySearch(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []) : array;

    /**
     * Find and delete a row by its ID from the device's storage.
     *
     * @param array $conditions
     * @return boolean
     */
    public function findByIDandDelete(array $conditions) : bool;

    /**
     * Update the queried row and return true on success.
     * The second argument specifies which column to update by within the
     * WHERE clause.
     *
     * @param integer $id
     * @param array $fields
     * @return boolean
     */
    public function findByIDandUpdate(int $id, array $fields = []) : bool;

    /**
     * Returns the storage data as an array along with paginated results.
     * This method will also returns queried search results.
     *
     * @param array $arguments
     * @param object $request
     * @return array
     */

     /**
      * Undocumented function
      *
      * @param object $request
      * @param array $args
      * @return array
      */
    public function findWithSearchAndPaging(object $request, array $args = []) : array;

    /**
     * Find and item by its ID and return the object row else return 404
     * with the or404 chaining method.
     *
     * @param integer $id
     * @param array $selectors
     * @return self
     */
    public function findAndReturn(int $id, array $selectors = []) : self;

    /**
     * Returns 404 error page if the findAndReturn method or property returns
     * empty or null.
     *
     * @return object|null
     */
    public function or404() : ?object;
}
