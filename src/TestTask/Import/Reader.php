<?php

namespace TestTask\Import;


use Monolog\Logger;

class Reader
{
    private string $filePath;
    private $file;
    private bool $isOpen = false;
    private Logger $logger;

    public function __construct(Logger $logger, $path)
    {
        $this->logger = $logger;
        $this->filePath = $path;

    }

    public function open(): void
    {
        if ($this->file = fopen($this->filePath, 'r')) {
            $this->isOpen = true;
        } else {
            $this->logger->error("cant read file: ".$this->filePath.PHP_EOL);
            die();
        }
    }

    public function close(): void
    {
        if ($this->isOpen()) {
            fclose($this->file);
            $this->isOpen = false;
        }
    }

    public function isOpen(): bool
    {
        return $this->isOpen;
    }

    public function readFile(): \Generator
    {
        while (!feof($this->file)) {
            yield fgetcsv($this->file);
        }

        return;
    }
}
