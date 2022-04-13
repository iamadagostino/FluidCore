<?php

declare(strict_types=1);

namespace Fluid\LiquidORM\DataRepository;

use Fluid\LiquidORM\DataRepository\Exception\DataRepositoryInvalidArgumentException;
use Fluid\LiquidORM\EntityManager\EntityManagerInterface;
use Throwable;

class DataRepository implements DataRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $em;

    /**
     * Main constructor method.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Checks whether the arguement is of the array type else throw an exception.
     *
     * @param array $conditions
     * @return void
     * @throws DataRepositoryInvalidArgumentException
     */
    private function isArray(array $conditions) : void
    {
        if (!is_array($conditions)) {
            throw new DataRepositoryInvalidArgumentException('The argument supplied is not an array.');
        }
    }

    /**
     * Checks whether the argument is set else throw an exception
     *
     * @param integer $id
     * @return void
     * @throws DataRepositoryInvalidArgumentException
     */
    private function isEmpty(int $id) : void
    {
        if (empty($id)) {
            throw new DataRepositoryInvalidArgumentException('The argument should not be empty.');
        }
    }

    /**
     * @inheritDoc
     *
     * @param integer $id
     * @return array
     * @throws Throwable
     */
    public function find(int $id): array
    {
        $this->isEmpty($id);

        try {
            return $this->findOneBy(['id' => $id]);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     *
     * @param array $conditions
     * @return array
     * @throws Throwable
     */
    public function findOneBy(array $conditions): array
    {
        $this->isArray($conditions);

        try {
            return $this->em->getCRUD()->read([], $conditions);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     *
     * @return array
     * @throws Throwable
     */
    public function findAll(): array
    {
        try {
            return $this->em->getCRUD()->read();
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     *
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $optional
     * @return array
     * @throws Throwable
     */
    public function findBy(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
    {
        $this->isArray($conditions);

        try {
            return $this->em->getCRUD()->read($selectors, $conditions, $parameters, $optional);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     *
     * @param array $conditions
     * @param array $selectors
     * @return object
     */
    public function findObjectBy(array $conditions = [], array $selectors = []): object
    {
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $optional
     * @return array
     * @throws Throwable
     */
    public function findBySearch(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
    {
        $this->isArray($conditions);

        try {
            return $this->em->getCRUD()->search($selectors, $conditions, $parameters, $optional);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     *
     * @param array $conditions
     * @return boolean
     * @throws Throwable
     */
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

    /**
     * @inheritDoc
     *
     * @param integer $id
     * @param array $fields
     * @return boolean
     * @throws Throwable
     */
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

    /**
     * @inheritDoc
     *
     * @param array $arguments
     * @param object $request
     * @return array
     * @throws Throwable
     */
    public function findWithSearchAndPaging(object $request, array $args = []) : array
    {
        return [];
    }

    public function findAndReturn(int $id, array $selectors = []): DataRepositoryInterface
    {
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @return object|null
     */
    public function or404(): ?object
    {
        return null;
    }
}
