<?php


use PHPUnit\Framework\TestCase;

class MemcachedTest extends TestCase
{

    public function testMakeHash()
    {
        $memcached = new \TestTask\Memcached(new \TestTask\AppData());
        $this->assertEquals(
            '81252f4008c8ca7a56359294df93372bdcebd1cb81d20f0426b3b2e6364bb7fd8de726dace4b4ff5c47c7bf199af1f01a349d23431e5d8240d517008488ea350',
            $memcached->makeHash(['name' => 'qwe'])

        );
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