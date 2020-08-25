<?php

namespace TestTask\Import\Csv;


use TestTask\AppData;
use TestTask\Database\Database;
use TestTask\Import\ImportInterface;
use TestTask\Import\Reader;
use TestTask\UserDatabaseConfig;

class Import implements ImportInterface
{

    private AppData $app;
    private Reader $reader;
    private RowValidator $validator;
    private int $batchSize = 50000;
    private Database $db;
    private int $total = 0;
    private int $wrongLinesTotal = 0;

    public function __construct(AppData $app, Reader $reader)
    {
        $this->app = $app;
        $this->reader = $reader;
        $this->db = $this->app->getDatabase();
        $this->validator = new RowValidator($this->app->getLogger(), UserDatabaseConfig::FIELD_SET);
    }


    public function process(): void
    {
        $this->reader->open();
        if ($this->reader->isOpen()) {
            $batch = [];
            $this->db->beginTransaction();
            foreach ($this->reader->readFile() as $line => $row) {
                if (empty($row)) {
                    $this->wrongLinesTotal++;
                    break;
                }
                if ($this->validator->validateRow($row, $line)) {
                    array_push($batch, ...$row);
                    $this->total++;
                    if ($this->total % $this->batchSize === 0) {
                        $this->import($batch);
                        $batch = [];
                    }
                } else {
                    $this->wrongLinesTotal++;
                }
            }
            if (!empty($batch)) {
                $this->import($batch);
            }
            $this->app->getLogger()->info(sprintf('Valid Lines: %s', $this->total).PHP_EOL);
            if ($this->wrongLinesTotal) {
                $this->app->getLogger()->warning(sprintf('Wrong Lines: %s', $this->wrongLinesTotal).PHP_EOL);
            }
            $this->db->commit();

        }
    }

    private function import($data): void
    {
        $this->db->import(
            UserDatabaseConfig::TABLE,
            array_keys(UserDatabaseConfig::FIELD_SET),
            UserDatabaseConfig::PRIMARY_KEY,
            $data
        );
        $this->app->getLogger()->info(sprintf('Batch with size %s imported', count($data) / 5).PHP_EOL);

    }

}
