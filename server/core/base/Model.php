<?php

namespace core\base;

use core\Db;

abstract class Model
{
    protected Db $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Db::getInstance();
    }

    /**
     * @param string $sql
     * @param array<int|string> $params
     * 
     * @return bool
     */
    public function query(string $sql, array $params = []): bool
    {
        return $this->db->execute($sql, $params);
    }

    /**
     * @return array<array<string,string|int|float>>
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->query($sql);
    }

    /**
     * @param string|int $fieldValue
     * @param string|null $field
     * 
     * @return array<string,string|int|float>
     */
    public function findOne($fieldValue, ?string $field = null): array
    {
        $field = $field ?? $this->primaryKey;

        $sql = "SELECT * FROM {$this->table} WHERE $field = ? LIMIT 1";

        $result = $this->db->query($sql, [$fieldValue]);
        if (count($result)) {
            $result = $result[0];
        }

        return $result;
    }

    /**
     * @param array<string|int|float> $params
     * @param array<string> $fields
     * 
     * @return bool
     */
    public function insertOne(array $params, array $fields = []): bool
    {
        if (count($params) === 0) {
            throw new \PDOException('No values to insert into table');
        }

        $sql = "INSERT INTO {$this->table} ";

        if (count($fields) > 0) {
            $sql .= 'SET ';

            foreach ($fields as $field) {
                $sql .= "$field=?,";
            }

            $sql = substr($sql, 0, strlen($sql) - 1);
        } else {
            $sql .= 'VALUES (';

            foreach ($params as $param) {
                $sql .= '?,';
            }

            $sql = substr($sql, 0, strlen($sql) - 1) . ')';
        }
        
        return $this->db->execute($sql, $params);
    }
}
