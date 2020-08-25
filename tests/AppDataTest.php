<?php

use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class AppDataTest extends TestCase
{

    /**
     * @covers \TestTask\AppData::getOptions
     */
    public function testOptions_validInstance(): void
    {
        $appData = new \TestTask\AppData();
        $this->assertInstanceOf(
            \stdClass::class,
            $appData->getOptions()
        );
    }

    /**
     * @covers \TestTask\AppData::getLogger
     */
    public function testGetLogger(): void
    {
        $appData = new \TestTask\AppData();
        $this->assertInstanceOf(
            Logger::class,
            $appData->getLogger()
        );
    }

    /**
     * @covers \TestTask\AppData::getDatabase
     */
    public function testGetDatabase(): void
    {
        $appData = new \TestTask\AppData();
        $this->assertInstanceOf(
            \TestTask\Database\Database::class,
            $appData->getDatabase()
        );
    }

    /**
     * @covers \TestTask\AppData::getEnv
     */
    public function testGetEnv(): void
    {
        $appData = new \TestTask\AppData();
        $this->assertInstanceOf(
            \TestTask\Env::class,
            $appData->getEnv()
        );
    }
}