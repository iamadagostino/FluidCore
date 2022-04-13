<?php

declare(strict_types=1);

namespace Fluid\LiquidORM\DataMapper;

use Fluid\DB\DatabaseConnectionInterface;
use Fluid\LiquidORM\DataMapper\Exception\DataMapperException;

class DataMapperFactory
{
    /**
     * Main constructor method
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Creates the Data Mapper object and inject the dependency for this object.
     * Also, creating the DatabaseConnection object.
     * The $dataMapperEnvironmentConfiguration get instantiated in
     * the DataRepositoryFactory.
     *
     * @param string $dbConnectionString
     * @param string $dataMapperEnvironmentConfiguration
     * @return DataMapperInterface
     * @throws DataMapperException
     */
    public function create(string $dbConnectionString, string $dataMapperEnvironmentConfiguration) : DataMapperInterface
    {
        $credentials = (new $dataMapperEnvironmentConfiguration([]))->getDatabaseCredentials('mysql');
        $dbConnectionObject = new $dbConnectionString($credentials);

        if (!$dbConnectionObject instanceof DatabaseConnectionInterface) {
            throw new DataMapperException($dbConnectionString . ' is not a valid DB\'s connection object.');
        }

        return new DataMapper($dbConnectionObject);
    }
}
