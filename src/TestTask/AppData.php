<?php

namespace TestTask;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use TestTask\Database\Connector\Mysql\Connector;
use TestTask\Database\Database;

class AppData
{

    private Env $env;
    private Database $database;
    private Logger $logger;
    private \stdClass $options;

    public function __construct()
    {
        $this->logger = new Logger('testApp');
        $this->env = new Env($this->logger);
        $this->logger->pushHandler(new StreamHandler('php://stdout', Logger::INFO));
        $this->options = (new ConsoleOptions($this->logger))->options;
        $this->database = new Database(new Connector($this->env));

    }

    public function getOptions(): \stdClass
    {
        return $this->options;
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }

    public function getEnv(): Env
    {
        return $this->env;
    }

    public function getLogger(): Logger
    {
        return $this->logger;
    }
}