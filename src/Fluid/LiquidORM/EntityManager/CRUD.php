<?php

declare(strict_types=1);

namespace Fluid\LiquidORM\EntityManager;

use Throwable;
use Fluid\LiquidORM\DataMapper\DataMapper;
use Fluid\LiquidORM\QueryBuilder\QueryBuilder;

class CRUD implements CRUDInterface
{
    /**
     * @var DataMapper
     */
    protected DataMapper $dataMapper;

    /**
     * @var QueryBuilder
     */
    protected QueryBuilder $queryBuilder;

    /**
     * @var string
     */
    protected string $tableSchema;

    /**
     * @var string
     */
    protected string $tableSchemaID;

    /**
     * @var array
     */
    protected array $options;

    /**
     * Main constructor method
     *
     * @param DataMapper $dataMapper
     * @param QueryBuilder $queryBuilder
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(
        DataMapper $dataMapper,
        QueryBuilder $queryBuilder,
        string $tableSchema,
        string $tableSchemaID,
        ?array $options = []
    ) {
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
        $this->options = $options;
    }

    /**
     * @inheritDoc
     *
     * @return string
     */
    public function getSchema(): string
    {
        return (string)$this->tableSchema;
    }

    /**
     * @inheritDoc
     *
     * @return string
     */
    public function getSchemaID() : string
    {
        return (string)$this->tableSchemaID;
    }


    /**
     * @inheritDoc
     *
     * @return integer
     */
    public function lastID(): int
    {
        return (int)$this->dataMapper->getLastID();
    }


    /**
     * @inheritDoc
     *
     * @param array $fields
     * @return boolean
     */
    public function create(array $fields = []) : bool
    {
        try {
            $args = [
              'table' => $this->getSchema(),
              'type' => 'insert',
              'fields' => $fields
            ];
            $query = $this->queryBuilder->buildQuery($args)->insertQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));

            if ($this->dataMapper->rowsCount() == 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }


    /**
     * @inheritDoc
     *
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $optional
     * @return array
     */
    public function read(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
    {
        try {
            $args = [
              'table' => $this->getSchema(),
              'type' => 'select',
              'selectors' => $selectors,
              'conditions' => $conditions,
              'params' => $parameters,
              'extras' => $optional
            ];
            $query = $this->queryBuilder->buildQuery($args)->selectQuery;
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions, $parameters));

            if ($this->dataMapper->rowsCount() > 0) {
                return $this->dataMapper->results();
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }


    /**
     * @inheritDoc
     *
     * @param string $pk
     * @param array $fields
     * @return boolean
     */
    public function update(string $pk, array $fields = []): bool
    {
        try {
            $args = [
              'table' => $this->getSchema(),
              'type' => 'update',
              'pk' => $pk,
              'fields' => $fields
            ];
            $query = $this->queryBuilder->buildQuery($args)->updateQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));

            if ($this->dataMapper->rowsCount() == 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     *
     * @param array $conditions
     * @return boolean
     */
    public function delete(array $conditions = []): bool
    {
        try {
            $args = [
              'table' => $this->getSchema(),
              'type' => 'delete',
              'conditions' => $conditions
            ];
            $query = $this->queryBuilder->buildQuery($args)->deleteQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));

            if ($this->dataMapper->rowsCount() == 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     *
     * @param array $selectors
     * @param array $conditions
     * @return array
     */
    public function search(array $selectors, array $conditions = []): array
    {
        try {
            $args = [
              'table' => $this->getSchema(),
              'type' => 'search',
              'selectors'=> $selectors,
              'conditions' => $conditions
            ];
            $query = $this->queryBuilder->buildQuery($args)->deleteQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));

            if ($this->dataMapper->rowsCount() > 0) {
                return $this->dataMapper->results();
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }


    /**
     * @inheritDoc
     *
     * @param string $raw
     * @param array|null $conditions
     * @return mixed
     */
    public function raw(string $raw, ?array $conditions = []): mixed
    {
        try {
            $args = [
                'table' => $this->getSchema(),
                'type' => 'raw',
                'raw'=> $raw,
                'conditions' => $conditions
            ];
            $query = $this->queryBuilder->buildQuery($args)->rawQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));

            if ($this->dataMapper->rowsCount()) {
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }
}
