<?php

declare(strict_types=1);

namespace Fluid\LiquidORM\QueryBuilder;

use Fluid\LiquidORM\QueryBuilder\QueryBuilderInterface;
use Fluid\LiquidORM\QueryBuilder\Exception\QueryBuilderException;

class QueryBuilderFactory
{
    /**
     * Main constructor method
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function create(string $queryBuilderString) : QueryBuilderInterface
    {
        $queryBuilderObject = new QueryBuilderString();
      
        if (!$queryBuilderObject instanceof QueryBuilderInterface) {
            throw new QueryBuilderException($queryBuilderString . 'is not a valid Query Builder Object');
        }

        return $queryBuilderString;
    }
}
