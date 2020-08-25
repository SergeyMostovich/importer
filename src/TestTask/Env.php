<?php

namespace TestTask;


use Dotenv\Dotenv;
use Monolog\Logger;

class Env
{
    private Dotenv $env;
    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
        $this->env = Dotenv::createImmutable(
            __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR
        );
        $this->env->load();
    }

    public function getEnvVariable(string $var)
    {

        if (!empty($_ENV[$var])) {
            return $_ENV[$var];
        } else {
            throw new MissingEnvVariableException(sprintf('missing %s variable in .env', $var));
        }

    }

}