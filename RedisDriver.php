<?php

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\MultipleInterface;
use Psr\SimpleCache\IncrementableInterface;

/**
* Redis cache driver.
*
* @author     Paul Dragoonis <paul@ppi.io>
* @package    PPI
* @subpackage Cache
*/
class RedisDriver implements CacheInterface
{

    protected $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    public function get($key)
    {
        return $this->redis->get($key);
    }

    public function set($key, $value, $ttl = null)
    {
        return $this->redis->set($key, $value, $ttl);
    }

    public function delete($key)
    {
        $this->redis->delete($key);
    }

    public function clear()
    {
        $this->redis->flushAll();
    }

    public function getMultiple($keys)
    {
        $cacheValues = array_combine($keys, $this->redis->mGet($keys));

        foreach ($cacheValues as $key => $value) {
            if($value === false && !$this->redis->exists($key)) {
                continue;
            }
            $ret[$key] = $value;
        }

        return $ret;
    }

    public function setMultiple($data, $ttl = null)
    {
        // No native TTL support for MSET so we use a transaction
        $transaction = $this->redis->multi();
        foreach ($data as $key => $val) {
            $transaction->set($key, $val, $ttl);
        }

        $res = $transaction->exec();
        foreach($res as $key => $value) {
            if($value === false) {
                return false;
            }
        }

        return true;
    }

    public function deleteMultiple($keys)
    {
        $transaction = $this->redis->multi();
        foreach($keys as $key) {
            $transaction->del($key);
        }
        $transaction->exec();
    }

    public function increment($key, $step = 1)
    {
        $this->redis->incrBy($key, $step);
    }

    public function decrement($key, $step = 1)
    {
        $this->redis->decrBy($key, $step);
    }
}
