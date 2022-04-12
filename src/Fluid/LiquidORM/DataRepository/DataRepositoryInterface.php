<?php

declare(strict_types=1);

namespace Fluid\LiquidORM\DataRepository;

interface DataRepositoryInterface
{
    public function find(int $id) : array;

    public function findAll() : array;

    public function findBy(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []) : array;

    public function findOneBy(array $conditions) : array;

    public function findObjectBy(array $conditions = [], array $selectors = []) : object;

    public function findBySearch(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []) : array;

    public function findByIDandDelete(array $conditions) : bool;

    public function findByIDandUpdate(int $id, array $fields = []) : bool;

    public function findWithSearchAndPaging(array $arguments, object $request) : array;

    public function findAndReturn(int $id, array $selectors = []) : self;
}
