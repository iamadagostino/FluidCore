<?php

declare(strict_types=1);

namespace Fluid\LiquidORM\EntityManager;

use Fluid\LiquidORM\DataMapper\DataMapperInterface;
use Fluid\LiquidORM\QueryBuilder\QueryBuilderInterface;
use Fluid\LiquidORM\EntityManager\EntityManagerInterface;
use Fluid\LiquidORM\EntityManager\Exception\CRUDException;

class EntityManagerFactory
{
    /**
     * @inheritDoc
     *
     * @var DataMapperInterface
     */
    protected DataMapperInterface $dataMapper;

    /**
     * @inheritDoc
     *
     * @var QueryBuilderInterface
     */
    protected QueryBuilderInterface $queryBuilder;

    /**
     * Main constructor method
     *
     * @param DataMapperInterface $dataMapper
     * @param QueryBuilderInterface $queryBuilder
     */
    public function __construct(DataMapperInterface $dataMapper, QueryBuilderInterface $queryBuilder)
    {
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @inheritDoc
     *
     * @param string $crudString
     * @param string $tableSchema
     * @param string $tablSchemaID
     * @param array $options
     * @return EntityManagerInterface
     */
    public function create(string $crudString, string $tableSchema, string $tablSchemaID, array $options = []) : EntityManagerInterface
    {
        $crudObject = new $crudString($this->dataMapper, $this->queryBuilder, $tableSchema, $tablSchemaID);
        if (!$crudObject instanceof CRUDInterface) {
            throw new CRUDException($crudString . 'is not a valid CRUD object.');
        }

        return new EntityManager($crudObject);
    }
}
