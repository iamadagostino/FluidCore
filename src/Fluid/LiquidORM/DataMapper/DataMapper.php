<?php

declare(strict_types=1);

namespace Fluid\LiquidORM\DataMapper;

use PDO;
use Throwable;
use PDOStatement;
use Fluid\DB\DatabaseConnectionInterface;
use Fluid\LiquidORM\DataMapper\Exception\DataMapperException;

class DataMapper implements DataMapperInterface
{
    /**
     * @var DatabaseConnectionInterface
     */
    private DatabaseConnectionInterface $dbh;

    /**
     * @var PDOStatement
     */
    private PDOStatement $stmt;

    /**
     * Main constructor method
     */
    public function __construct(DatabaseConnectionInterface $dbh)
    {
        $this->dbh = $dbh;
    }

    /**
     * Checks if the incoming $value isn't empty else throws an exception
     *
     * @param mixed $value
     * @param string|null $errorMessage
     *
     * @return void
     *
     * @throws DataMapperException
     */
    private function isEmpty(mixed $value, string $errorMessage = null) : void
    {
        if (empty($value)) {
            throw new DataMapperException($errorMessage);
        }
    }

    /**
     * Checks if the incoming $value is ana array else throws an exception
     *
     * @param mixed $value
     * @param string|null $errorMessage
     *
     * @return void
     *
     * @throws DataMapperException
     */
    private function isArray(mixed $value, string $errorMessage = null) : void
    {
        if (is_array($value)) {
            throw new DataMapperException('The argument needs to be an array');
        }
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $query) : static
    {
        $this->stmt = $this->dbh->open()->prepare($query);

        return $this ;
    }

    /**
     * @inheritDoc
     */
    public function bind(mixed $value): mixed
    {
        try {
            switch ($value) {
              case is_bool($value):
              case intval($value):
                $dataType = PDO::PARAM_INT;
                break;
              case is_null($value):
                $dataType = PDO::PARAM_NULL;
                break;
              default:
                $dataType = PDO::PARAM_STR;
                break;
            }
            
            return $dataType;
        } catch (DataMapperException $exception) {
            throw new $exception;
        }
    }

    /**
     * @inheritDoc
     */
    public function bindParameters(array $fields, bool $isSearch = false) : self
    {
        if (is_Array($fields)) {
            $type = ($isSearch === false) ? $this->bindValues($fields) : $this->bindSearchValues($fields);

            if ($type) {
                return $this;
            }
        }

        return false;
    }

    /**
     * Binds a value to a corresponding name or question mark placeholder
     * in the SQL statement that was used to prepare the statement
     *
     * @param array $fields
     *
     * @return PDOStatement
     *
     * @throws DataMapperException
     */
    protected function bindValues(array $fields) : PDOStatement
    {
        $this->isArray($fields);

        foreach ($fields as $key => $value) {
            $this->stmt->bindValue(':' . $key, $value, $this->bind($value));
        }

        return $this->stmt;
    }

    /**
     * Similar to bindValues function above but optimized for searh queries
     *
     * @param $fields
     *
     * @return PDOStatement
     *
     * @throws DataMapperException
     */
    protected function bindSearchValues(array $fields) : PDOStatement
    {
        $this->isArray($fields);

        foreach ($fields as $key => $value) {
            $this->stmt->bindValue(':' . $key, '%' . $value . '%', $this->bind($value));
        }

        return $this->stmt;
    }

    /**
     * @inheritDoc
     */
    public function execute(): mixed
    {
        if ($this->stmt) {
            return $this->stmt->execute();
        }
    }

    /**
     * @inheritDoc
     */
    public function rowsCount(): int
    {
        if ($this->stmt) {
            return $this->stmt->rowCount();
        }
    }

    /**
     * @inheritDoc
     */
    public function result(): object
    {
        if ($this->stmt) {
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        }
    }

    /**
     * @inheritDoc
     */
    public function results(): array
    {
        if ($this->stmt) {
            return $this->stmt->fetchAll();
        }
    }

    /**
     * @inheritDoc
     *
     * @throws Throwable
     */
    public function getLastID(): int
    {
        try {
            if ($this->dbh->open()) {
                $lastID = $this->dbh->open()->lastInsertId;
                if (!empty($lastID)) {
                    return intval($lastID);
                }
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * Returns the query conditions merged with the query paramaters
     *
     * @param array $conditions = []
     * @param array $parameters = []
     *
     * @return array
     */
    public function buildQueryParameters(array $conditions = [], array $parameters = []) : array
    {
        return (!empty($conditions) || !empty($parameters)) ? array_merge($conditions, $parameters) : $parameters;
    }

    /**
     * Persist queries to DB
     *
     * @param string $query
     * @param array $parameters
     *
     * @return mixed
     *
     * @throws Throwable
     */
    public function persist(string $query, array $parameters) : mixed
    {
        try {
            return $this->prepare($query)->bindParameters($parameters)->execute();
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }
}
