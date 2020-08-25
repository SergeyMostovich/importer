<?php


namespace TestTask;


class Memcached
{

    private \Memcached $memcached;
    private AppData $appData;

    public function __construct(AppData $appData)
    {
        $this->memcached = new \Memcached();
        $this->appData = $appData;
        try {
            $this->memcached->addServer(
                $this->appData->getEnv()->getEnvVariable('MEMCACHED_HOST'),
                $this->appData->getEnv()->getEnvVariable('MEMCACHED_PORT')
            );
        } catch (MissingEnvVariableException $e) {
            $this->logger->error($e->getMessage());
        }
    }

    public function set(string $hash, $data)
    {
        $this->memcached->set($hash, $data);
    }

    public function get(string $hash): string
    {
        return $this->memcached->get($hash);
    }

    public function makeHash(array $searchData)
    {
        $string = $this->appData->getOptions()->limit;
        foreach ($searchData as $name => $value) {
            $string .= $name.$value;
        }

        return hash('sha512', $string);

    }

}