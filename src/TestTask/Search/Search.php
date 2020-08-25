<?php


namespace TestTask\Search;


use TestTask\AppData;
use TestTask\Memcached;
use TestTask\UserDatabaseConfig;

class Search
{
    private AppData $app;
    private Memcached $memcached;

    public function __construct(AppData $app)
    {
        $this->app = $app;
        $this->memcached = new Memcached($app);
        $this->checkSearchData();
        $this->getData();
    }

    private function checkSearchData()
    {
        try {
            if (empty($this->app->getOptions()->search)) {
                throw new \Exception('Empty search params');
            }
        } catch (\Exception $e) {
            $this->app->getLogger()->error($e->getMessage());
        }
    }

    private function getData()
    {
        $hash = $this->memcached->makeHash($this->app->getOptions()->search);
        if (!$this->app->getOptions()->forceCacheUpdate && $data = $this->memcached->get($hash)) {
            $data = json_decode($data);
        } else {
            $data = $this->app->getDatabase()->fetchArray(
                UserDatabaseConfig::TABLE,
                $this->app->getOptions()->search,
                $this->app->getOptions()->limit
            );
            $this->memcached->set($hash, json_encode($data));
        }
        print_r($data);
    }
}