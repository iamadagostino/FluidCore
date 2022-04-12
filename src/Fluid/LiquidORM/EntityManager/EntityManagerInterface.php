<?php

declare(strict_types=1);

namespace Fluid\LiquidORM\EntityManager;

interface EntityManagerInterface
{
    /**
     * Get the CRUD object which will expose all the methods within the CRUD class.
     *
     * @return object
     */
    public function getCRUD() : object;
}
