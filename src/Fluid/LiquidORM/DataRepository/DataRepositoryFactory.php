<?php

declare(strict_types=1);

namespace Fluid\LiquidORM\DataRepository;

use Fluid\LiquidORM\DataRepository\Exception\DataRepositoryException;

class DataRepositoryFactory
{
    protected string $tableSchema;

    protected string $tableSchemaID;

    protected string $crudIdentifier;

    public function __construct(string $crudIdentifier, string $tableSchema, string $tableSchemaID)
    {
        $this->crudIdentifier = $crudIdentifier;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    public function create(string $dataRepositoryString)
    {
        $dataRepositoryObject = new $dataRepositoryString();

        if (!$dataRepositoryObject instanceof DataRepositoryInterface) {
            throw new DataRepositoryException($dataRepositoryString . 'is not a valid Repository object.');
        }

        return $dataRepositoryObject; 
    }
}
