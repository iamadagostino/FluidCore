<?php

declare(strict_types=1);

namespace Fluid\LiquidORM\DataRepository;

use Fluid\LiquidORM\DataRepository\Exception\DataRepositoryInvalidArgumentException;
use Fluid\LiquidORM\EntityManager\EntityManagerInterface;
use Throwable;

class DataRepository implements DataRepositoryInterface
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    private function isArray(array $conditions) : void
    {
        if (!is_array($conditions)) {
            throw new DataRepositoryInvalidArgumentException('The argument supplied is not an array.');
        }
    }

    private function isEmpty(int $id) : void
    {
        if (empty($id)) {
            throw new DataRepositoryInvalidArgumentException('The argument should not be empty.');
        }
    }

    public function find(int $id): array
    {
        $this->isEmpty($id);

        try {
            return $this->findOneBy(['id' => $id]);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    public function findOneBy(array $conditions): array
    {
        $this->isArray($conditions);

        try {
            return $this->em->getCRUD()->read([], $conditions);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    public function findAll(): array
    {
        try {
            return $this->em->getCRUD()->read();
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    public function findBy(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
    {
        $this->isArray($conditions);

        try {
            return $this->em->getCRUD()->read($selectors, $conditions, $parameters, $optional);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    public function findObjectBy(array $conditions = [], array $selectors = []): object
    {
        return $this;
    }

    public function findBySearch(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
    {
        $this->isArray($conditions);

        try {
            return $this->em->getCRUD()->search($selectors, $conditions, $parameters, $optional);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    public function findByIDandDelete(array $conditions): bool
    {
        $this->isArray($conditions);

        try {
            $result = $this->findOneBy($conditions);

            if ($result != null && count($result) > 0) {
                $delete = $this->em->getCRUD()->delete($conditions);

                if ($delete) {
                    return true;
                }
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    public function findByIDandUpdate(int $id, array $fields = []): bool
    {
        $this->isArray($fields);

        try {
            $result = $this->findOneBy([$this->em->getCRUD()->getSchemaID() => $id]);

            if ($result != null && count($result) > 0) {
                $params = (!empty($fields)) ? array_merge(['id' => $id, $fields]) : $fields;
                $update = $this->em->getCRUD()->update($params, $this->em->getCRUD()->getSchemaID());

                if ($update) {
                    return true;
                }
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    public function findWithSearchAndPaging(array $arguments, object $request): array
    {
        return [];
    }

    public function findAndReturn(int $id, array $selectors = []): DataRepositoryInterface
    {
        return $this;
    }
}
