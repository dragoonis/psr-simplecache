<?php

namespace Psr\SimpleCache;

interface CacheInterface
{

    const TTL_MINUTE = 60;
    const TTL_HOUR = 3600;
    const TTL_DAY = 86400;

    const SUPPORTS_MULTIPLE = 'multiple';
    const SUPPORTS_INCDEC = 'incdec';

    /**
     * Here we pass in a cache key to be fetched from the cache.
     *
     * @param string $key The unique key of this item in the cache
     *
     * @return mixed The value of the item from the cache
     */
    public function get($key);

    /**
     * Persisting our data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string       $key   The key of the item to store
     * @param mixed        $value The value of the item to store
     * @param null|integer $ttl   Optional. The TTL value of this item. If no value is sent and the driver supports TTL
     *                            then the library may set a default value for it or let the driver take care of that.
     *
     * @return boolean True on success and false otherwise
     */
    public function set($key, $value, $ttl = null);

    /**
     * Remove an item from the cache by its unique key
     *
     * @param string $key The unique cache key of the item to remove
     *
     * @return boolean    Returns true on success and otherwise false
     */
    public function remove($key);

    /**
     * This will wipe out the entire cache's keys
     *
     * @return boolean Returns true on success and otherwise false
     */
    public function clear();

    /*
     * To identify if the current Cache is implementing specific intefaces and supports specific types of functionality; such as Multiple or IncDec
     *
     * @param string $feature
     *
     * @return boolean True if the feature is supported. False if it is not
     */
    public function supports($feature);

}
