<?php

declare(strict_types=1);

namespace Fluid\LiquidORM\QueryBuilder;

interface QueryBuilderInterface
{
    public function insertQuery() : string;

    public function selectQuery() : string;

    public function updateQuery() : string;

    public function deleteQuery() : string;

    public function searchQuery() : string;

    public function rawQuery() : string;
}
