<?php

namespace TestTask\Database\Connector;

interface ConnectorInterface
{
    public function import(string $table, array $field_set, string $primary_key, array $data): void;

    public function fetchArray(string $table, array $data, int $limit): array;

    public function truncateTable(string $table): bool;

    public function beginTransaction(): void;

    public function commit(): void;
}