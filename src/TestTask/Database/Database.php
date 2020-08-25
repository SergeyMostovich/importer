<?php

namespace TestTask\Database;

use TestTask\Database\Connector\ConnectorInterface;
use TestTask\Database\Connector\Mysql\Connector;

class Database implements ConnectorInterface
{
    public Connector $connection;

    public function __construct(Connector $connector)
    {
        $this->connection = $connector;
    }

    public function truncateTable(string $table): bool
    {
        return $this->connection->truncateTable($table);
    }

    public function import(string $table, array $field_set, string $primary_key, array $data): void
    {
        try {
            $this->connection->import($table, $field_set, $primary_key, $data);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }


    public function fetchArray(string $table, array $data, int $limit): array
    {
        return $this->connection->fetchArray($table, $data, $limit);
    }

    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    public function commit(): void
    {
        $this->connection->commit();
    }

    public function createTable(string $table): bool
    {
        return $this->connection->createTable($table);
    }
}