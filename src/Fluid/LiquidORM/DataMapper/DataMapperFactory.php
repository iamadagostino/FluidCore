<?php

declare(strict_types=1);

namespace Fluid\LiquidORM\DataMapper;

use Fluid\DB\DatabaseConnectionInterface;
use Fluid\LiquidORM\DataMapper\Exception\DataMapperException;

class DataMapperFactory
{
    /**
     * Main construct class
     *
     * @return void
     */
    public function __construct()
    {
    }

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
