<?php

declare(strict_types=1);

namespace Fluid\LiquidORM;

use Fluid\DB\DatabaseConnection;
use Fluid\LiquidORM\EntityManager\CRUD;
use Fluid\LiquidORM\QueryBuilder\QueryBuilder;
use Fluid\LiquidORM\DataMapper\DataMapperFactory;
use Fluid\LiquidORM\QueryBuilder\QueryBuilderFactory;
use Fluid\LiquidORM\EntityManager\EntityManagerFactory;
use Fluid\LiquidORM\DataMapper\DataMapperEnvironmentConfiguration;

class LiquidORM
{
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
     * @var DataMapperEnvironmentConfiguration
     */
    protected DataMapperEnvironmentConfiguration $environment;

    /**
     * Main constructor method.
     *
     * @param DataMapperEnvironmentConfiguration $dataMapperEnvironmentConfiguration
     * @param string $tableSchema
     * @param string $tableSchemaID
     * @param array|null $options
     */
    public function __construct(DataMapperEnvironmentConfiguration $dataMapperEnvironmentConfiguration, string $tableSchema, string $tableSchemaID, ?array $options = [])
    {
        $this->dataMapperEnvironmentConfiguration = $dataMapperEnvironmentConfiguration;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
        $this->options = $options;
    }

    /**
     * Initialize method which glues all the components together and injects
     * the necessary dependency within the respective object.
     *
     * @return object
     */
    public function initialize() : object
    {
        $dataMapperFactory = new DataMapperFactory();
        $dataMapper = $dataMapperFactory->create(DatabaseConnection::class, DatabaMapperEnvironmentConfiguration::class);

        if ($dataMapper) {
            $queryBuilderFactory = new QueryBuilderFactory();
            $queryBuilder = $queryBuilderFactory->create(QueryBuilder::class);

            if ($queryBuilder) {
                $entityManagerFactory = new EntityManagerFactory($dataMapper, $queryBuilder);
                return $entityManagerFactory->create(CRUD::class, $this->tableSchema, $this->tableSchemaID, $this->options);
            }
        }
    }
}
