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
class RedisDriver implements CacheInterface, MultipleInterface, IncrementableInterface
{

    protected $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    public function supports($feature)
    {
        switch($feature) {
            case self::SUPPORTS_MULTIPLE:
                return $this instanceof MultipleInterface;

            case self::SUPPORTS_INCDEC:
                return $this instanceof IncrementableInterface;

            default:
                return false;
        }
    }

    public function get($key)
    {
        return $this->redis->get($key);
    }

    public function set($key, $value = null, $ttl = null)
    {
        return $this->redis->set($key, $value, $ttl);
    }

    public function getMultiple($data)
    {
        $cacheValues = array_combine($data, $this->redis->mGet($data));

        foreach ($cacheValues as $key => $value) {
            if($value === false && !$this->redis->exists($key)) {
                unset($cacheValues[$key]);
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

    public function remove($key)
    {
        return $this->redis->delete($key) > 0;
    }

    public function removeMultiple($keys)
    {
        $transaction = $this->redis->multi();
        foreach($keys as $key) {
            $transaction->del($key);
        }

        $result = array_combine($keys, $transaction->exec());
        foreach($result as $key => $val) {
            $result[$key] = (bool) $val;
        }

        return $result;
    }

    public function clear()
    {
        return $this->redis->flushAll();
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
