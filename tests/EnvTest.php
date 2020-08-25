<?php


use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use TestTask\Env;
use TestTask\MissingEnvVariableException;

class EnvTest extends TestCase
{
    /**
     * @covers \TestTask\Env::getEnvVariable
     */
    public function testEnvVariableDoesNotExist()
    {
        $env = new Env(new Logger('test'));
        $this->expectException(MissingEnvVariableException::class);
        $env->getEnvVariable('invalid');
    }
}