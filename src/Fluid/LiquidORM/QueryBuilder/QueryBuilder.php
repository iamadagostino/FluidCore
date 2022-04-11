<?php

declare(strict_types=1);

namespace Fluid\LiquidORM\QueryBuilder;

use Fluid\LiquidORM\QueryBuilder\Exception\QueryBuilderInvalidArgumentException;

class QueryBuilder implements QueryBuilderInterface
{
    protected array $key;

    protected string $sqlQuery = '';

    protected const SQL_DEFAULT = [
      'and' => [],
      'conditions' => [],
      'distinct' => false,
      'fields' => [],
      'from' => [],
      'or' => [],
      'orderBy' => [],
      'pk' => '',
      'raw' => '',
      'replace' => false,
      'selectors' => [],
      'table' => '',
      'type' => '',
      'where' => null
    ];

    protected const QUERY_TYPES = [
        'insert',
        'select',
        'update',
        'delete',
        'search',
        'raw',
    ];

    private function isQueryTypeValid(string $type) : bool
    {
        if (in_array($type, self::QUERY_TYPES)) {
            return true;
        }

        return false;
    }

    private function hasConditions()
    {
        // To refactor
        if (isset($this->key['conditions']) && $this->key['conditions'] != '' && is_array($this->key['conditions'])) {
            $sort = [];

            foreach (array_keys($this->key['conditions']) as $whereKey => $where) {
                if (isset($where) && $where != '') {
                    $sort[] = $where . " = :" . $where;
                }
            }

            if (count($this->key['conditions']) > 0) {
                $this->sqlQuery .= " WHERE " . implode(" AND ", $sort);
            } elseif (empty($this->key['conditions'])) {
                $this->sqlQuery = " WHERE 1";
            }

            if (isset($this->key['orderBy']) && $this->key['orderBy'] != '') {
                $this->sqlQuery .= "ORDER BY " . $this->key['orderBy'] . " ";
            }

            if (isset($this->key['limit']) && $this->key['offset'] != -1) {
                $this->sqlQuery .= " LIMIT :offset, :limit";
            }

            return $this->sqlQuery;
        }
    }
    
    /**
     * Main constructor method
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function buildQuery(array $args = []) : self
    {
        if (count($args) < 0) {
            throw new QueryBuilderInvalidArgumentException();
        }

        $args = array_merge(self::SQL_DEFAULT, $args);

        $this->key = $args;

        return $this;
    }

    public function insertQuery(): string
    {
        // "INSERT INTO users (first_name, last_name, gender) VALUES (:first_name, :last_name, :gender)
        //
        // Example:
        // $user = [
        //   'first_name' => 'Angelo',
        //   'last_name' => 'D\'Agostino',
        //   'e-mail' => 'email@example.com',
        //   'gender' => 'Male'
        // ]
        if ($this->isQueryTypeValid('insert') && is_array($this->key['fields']) && count($this->key['fields']) > 0) {
            $index = array_keys($this->key['fields']);
            $value = array(implode(', ', $index), ":" . implode(', :', $index));

            $this->sqlQuery = "INSERT INTO {$this->key['table']} ({$value[0]}) VALUES({$value[1]})";

            return $this->sqlQuery;
        }

        return false;
    }

    public function selectQuery(): string
    {
        // "SELECT first_name, last_name, gender FROM users
        if ($this->isQueryTypeValid('select')) {
            $selectors = (!empty($this->key['selectors'])) ? implode(', ', $this->key['selectors']) : '*';

            $this->sqlQuery = "SELECT {$selectors} FROM {$this->key['table']}";

            $this->sqlQuery = $this->hasConditions();
        
            return $this->sqlQuery;
        }

        return false;
    }

    public function updateQuery(): string
    {
        // "UPDATE users SET (first_name, last_name, gender) WHERE pk = :pk LIMIT 1"
        if ($this->isQueryTypeValid('update') && is_array($this->key['fields']) && count($this->key['fields']) > 0) {
            $values = '';

            foreach ($this->key['fields'] as $field) {
                if ($field !== $this->key['pk']) {
                    $values .= $field . " = :" . $field . ", ";
                }

                $values = substr_replace($values, '', -2);

                if (count($this->key['fields']) > 0) {
                    $this->sqlQuery = "UPDATE {$this->key['table']} SET ({$values}) WHERE {$this->key['pk']} = :{$this->key['pk']} LIMIT 1";

                    if (isset($this->key['pk']) && $this->key['pk'] === '0') {
                        unset($this->key['pk']);
                        $this->sqlQuery = "UPDATE {$this->key['table']} SET ({$values})";
                    }
                }

                return $this->sqlQuery;
            }
        }

        return false;
    }

    public function deleteQuery(): string
    {
        // "DELETE FROM users WHERE {condition} = : {condition} LIMIT 1"
        if ($this->isQueryTypeValid('delete') && is_array($this->key['fields']) && count($this->key['fields']) > 0) {
            $index = array_keys($this->key['conditions']);

            $this->sqlQuery = "DELETE FROM {$this->key['table']} WHERE {$index[0]} = :{$index[0]} LIMIT 1";

            $bulkDelete = array_values($this->key['fields']);

            // "DELETE FROM users WHERE {condition} = : {condition}"
            if (is_array($bulkDelete) && count($bulkDelete) > 1) {
                for ($i = 0;  $i < count($bulkDelete); $i++) {
                    $this->sqlQuery = "DELETE FROM {$this->key['table']} WHERE {$index[0]} = :{$index[0]}";
                }
            }

            return $this->sqlQuery;
        }

        return false;
    }

    public function searchQuery() : string
    {
        return '';
    }

    public function rawQuery() : string
    {
        return '';
    }
}
