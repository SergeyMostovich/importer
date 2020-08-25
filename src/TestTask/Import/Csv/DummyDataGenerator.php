<?php

namespace TestTask\Import\Csv;


use Monolog\Logger;
use TestTask\Import\DummyDataGeneratorInterface;

class DummyDataGenerator implements DummyDataGeneratorInterface
{

    const CURRENCY_LIST = [
        'USD',
        'UAH',
        'CAD',
        'EUR',
    ];

    private int $userCount = 1000000;
    private string $path;
    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function setUsersCount(int $count): self
    {
        $this->userCount = $count;

        return $this;
    }


    public function build(): void
    {
        try {
            if (false === $file = fopen($this->path, "w")) {
                throw new \Exception('Unable to open file');
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            die;
        }
        for ($i = 1; $i <= $this->userCount; $i++) {
            fwrite($file, $this->makeUser($i));
        }
        fclose($file);
    }

    private function makeUser(int $id): string
    {
        return implode(
                ',',
                [
                    $id,
                    $this->makeDummyName($id),
                    $this->makeDummyEmail($id),
                    $this->getCurrency(),
                    $this->getTotal(),
                ]
            ).PHP_EOL;
    }

    private function makeDummyName(int $id): string
    {
        return "random user number {$id}";
    }

    private function makeDummyEmail(int $id): string
    {
        return "test{$id}@example.com";
    }

    private function getCurrency(): string
    {
        $currencyMethods = [
            $this->getRandomCurrency(),
            $this->getWrongCurrency(),
        ];

        return $currencyMethods[array_rand($currencyMethods)];
    }

    private function getRandomCurrency(): string
    {
        return $this::CURRENCY_LIST[array_rand($this::CURRENCY_LIST)];
    }

    private function getWrongCurrency(): string
    {
        return 'WRONG';
    }

    private function getTotal(): string
    {
        $currencyMethods = [
            $this->getRandomTotal(),
            $this->getWrongTotal(),
        ];

        return $currencyMethods[array_rand($currencyMethods)];
    }

    private function getRandomTotal(): float
    {
        return mt_rand(1 * 3, 10000 * 4) / 4;
    }

    private function getWrongTotal(): string
    {
        return 'WRONG';
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }
}
