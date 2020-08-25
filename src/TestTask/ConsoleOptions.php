<?php

namespace TestTask;


use Monolog\Logger;
use stdClass;
use TestTask\Import\ActionException;

class ConsoleOptions
{
    const VALID_OPTIONS = [
        'count' => 'count::',
        'action' => 'action:',
        'path' => 'path:',
        'type' => 'type:',
        'name' => 'name:',
        'email' => 'email:',
        'forceCacheUpdate' => 'forceCacheUpdate',
        'limit' => 'limit:',
    ];
    const VALID_ACTIONS = [
        'import',
        'search',
        'truncate',
        'generate',
        'createtable',
    ];
    const DEFAULT_OPTIONS = [
        'path' => '',
        'type' => '',
        'action' => '',
        'forceCacheUpdate' => false,
        'search' => [],
        'count' => 10,
        'limit' => 10,
    ];
    public stdClass $options;
    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
        $this->options = (object)(self::DEFAULT_OPTIONS);
        $consoleOpts = getopt(null, self::VALID_OPTIONS);
        try {
            if (empty($consoleOpts['action'])) {
                throw new ActionException('action option is required'.PHP_EOL);
            }
        } catch (ActionException $e) {
            $this->logger->error($e->getMessage());
        }
        foreach ($consoleOpts as $name => $value) {
            call_user_func([__CLASS__, 'set'.ucfirst($name)], ['name' => $name, 'value' => $value]);
        }

    }

    private function setPath(array $params)
    {
        try {
            if (file_exists($params['value'])) {
                $this->options->path = $params['value'];
            } else {
                throw new \Exception('file doesn\'t exist');
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            die;
        }

    }

    private function setCount(array $params): void
    {
        $this->options->count = (int)$params['value'];
    }

    private function setType(array $params): void
    {
        $this->options->type = (int)$params['value'];
    }

    private function setLimit(array $params): void
    {
        $this->options->limit = (int)$params['value'];
    }

    private function setName(array $params): void
    {
        $this->options->search['name'] = $params['value'];
    }

    private function setForceCacheUpdate(array $params): void
    {
        $this->options->forceCacheUpdate = true;
    }

    private function setEmail(array $params): void
    {
        $this->options->search['email'] = $params['value'];
    }

    private function setAction(array $params): void
    {
        try {
            if (in_array($params['value'], self::VALID_ACTIONS)) {
                $this->options->action = $params['value'];
            } else {
                throw new ActionException('wrong action');
            }

        } catch (ActionException $e) {
            $this->logger->error($e->getMessage());
            die ();
        }
    }
}