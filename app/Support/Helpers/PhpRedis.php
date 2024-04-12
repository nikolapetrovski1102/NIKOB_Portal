<?php

namespace App\Support\Helpers;
use Illuminate\Support\Facades\Config;
use Redis;
use RedisException;

class PhpRedis
{
    /**
     * @throws RedisException
     */
    public function getKey($key)
    {
        $redis = new Redis();
        $redis->connect(Config::get('redis.host'), Config::get('redis.port'));
        return $redis->get($key);
    }

    /**
     * @throws RedisException
     */
    public function setKey($key, $value, $expiry): bool
    {
        $redis = new Redis();
        $redis->connect(Config::get('redis.host'), Config::get('redis.port'));
        $redis->set($key, $value);
        $redis->expire($key, $expiry);
        return true;
    }

    /**
     * @throws RedisException
     */
    public function removeKey($key): false|int|Redis
    {
        $redis = new Redis();
        $redis->connect(Config::get('redis.host'), Config::get('redis.port'));
        return $redis->del($key);
    }

    /**
     * @throws RedisException
     */
    public function keyExists($key): bool
    {
        echo "The key is: $key";

        printf("The host is: " . Config::get('redis.host'));
        printf("The port is: " . Config::get('redis.port'));

        $redis = new Redis();
        $redis->connect(Config::get('redis.host'), Config::get('redis.port'));
        return false;
    }
}
