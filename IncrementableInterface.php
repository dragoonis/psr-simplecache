<?php

namespace Psr\SimpleCache;

interface IncrementableInterface
{

    /**
     * Increment a value in the cache by its step value, which defaults to 1
     *
     * @param string  $key  The cache item key
     * @param integer $step The value to increment by, defaulting to 1
     *
     * @return boolean True on success and false on failure
     */
    public function increment($key, $step = 1);

    /**
     * Decrement a value in the cache by its step value, which defaults to 1
     *
     * @param string  $key  The cache item key
     * @param integer $step The value to decrement by, defaulting to 1
     *
     * @return boolean True on success and false on failure
     */
    public function decrement($key, $step = 1);

}
