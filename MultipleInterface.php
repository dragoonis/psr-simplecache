<?php

namespace Psr\SimpleCache;

interface MultipleInterface
{

    /**
     * Obtain multiple cache items by their unique keys
     *
     * @param array|Traversable $keys A list of keys that can obtained in a single operation.
     *
     * @return array An array of items to cache. The array key is the value return from the cache. Items not found in the cache are not part of the return array. This is how you detect if an item exists or not.
      *
     */
    public function getMultiple($keys);

    /**
     * Persisting a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param array|Traversable   $items An array of key => value pairs for a multiple-set operation.
     * @param null|integer $ttl   Optional. The amount of seconds from the current time that the item will exist in the cache for. I this is null then the cache backend will fall back to its own default behaviour.
     *
     * @return boolean The result of the multiple-set operation
     */
    public function setMultiple($items, $ttl = null);

    /**
     * Remove multiple cache items in a single operation
     *
     * @param array|Traversable $keys The array of string-based Keys to be removed
     *
     * @return array An array of 'key' => result, elements. Each array row has the key being deleted
     *               and the result of that operation. The result will be a boolean of true or false
     *               representing if the cache item was removed or not.
     */
    public function removeMultiple($keys);

}
