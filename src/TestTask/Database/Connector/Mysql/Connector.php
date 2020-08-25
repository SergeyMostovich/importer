<?php

namespace TestTask\Database\Connector\Mysql;


use TestTask\Database\Connector\ConnectorInterface;
use TestTask\MissingEnvVariableException;

class Connector implements ConnectorInterface
{


    private \PDO $connection;

    public function __construct($env)
    {
        try {
            $dsn = sprintf(
                'mysql:dbname=%s;host=%s',
                $env->getEnvVariable('DATABASE_NAME'),
                $env->getEnvVariable('DATABASE_URL')
            );
            $user = $env->getEnvVariable('DATABASE_USER');
            $pass = $env->getEnvVariable('DATABASE_PASS');
            $this->connection = new \PDO($dsn, $user, $pass);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die($e->getMessage());
        } catch (MissingEnvVariableException $e) {
            $this->logger->error($e->getMessage());
        }

    }

    public function truncateTable(string $table): bool
    {
        return $this->executeQuery("TRUNCATE `{$table}`");
    }

    public function executeQuery(string $query, array $param = []): bool
    {
        $stmt = $this->connection->prepare($query);

        return $stmt->execute($param);
    }


    public function fetchArray(string $table, array $data, int $limit): array
    {

        $sql = "SELECT *
                FROM `{$table}`
                ";
        $whereStatement = [];
        foreach ($data as $name => $value) {
            $whereStatement[] = "{$name} = :{$name}";
        }
        $sql .= $whereStatement ? ' WHERE '.implode(' AND ', $whereStatement) : '';
        $sql .= " LIMIT {$limit}";
        $stmt = $this->prepare($sql);
        $stmt->execute($data);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        return $stmt->fetchAll();
    }

    private function prepare(string $string): \PDOStatement
    {
        return $this->connection->prepare($string);
    }

    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    public function commit(): void
    {
        $this->connection->commit();
    }

    public function import(string $table, array $field_set, string $primary_key, array $data): void
    {
        $rowCount = count($data) / count($field_set);
        $query = $this->buildQuery($table, $field_set, $primary_key, $rowCount);
        try {
            $this->executeQuery($query, $data);
        } catch (PDOException $e) {
            $this->rollback();
            throw $e;
        }
    }

    private function buildQuery(string $table, array $field_set, string $primary_key, int $rowCount = 1): string
    {
        $odkuFieldsSet = array_filter(
            $field_set,
            function ($field) use ($primary_key) {
                return $field != $primary_key;
            }
        );
        $odkuStatement = " ON DUPLICATE KEY UPDATE ".implode(
                ', ',
                array_map(
                    function ($field) {
                        return sprintf('%s=VALUES(%s)', $field, $field);
                    },
                    $odkuFieldsSet
                )
            );

        $values = sprintf("(%s)", implode(',', array_fill(0, count($field_set), '?')));

        return sprintf(
            'INSERT INTO %s (%s) VALUES %s %s',
            $table,
            implode(',', $field_set),
            implode(',', array_fill(0, $rowCount, $values)),
            $odkuStatement
        );
    }

    private function rollback(): void
    {
        $this->connection->rollback();
    }

    public function createTable(string $table): bool
    {
        return $this->executeQuery(
            "
                create table if not exists `{$table}`
                (
                	id int(11) auto_increment,
                	name varchar(32) not null,
                	email varchar(254) not null,
                	currency char(3) not null,
                	total decimal(15,4) not null,
                	constraint users_pk
                		primary key (id)
                );
                
                create index if not exists users_email_index
                	on `{$table}` (email);
                
                create index if not exists users_name_index
                	on `{$table}` (name);
                "
        );
    }

}