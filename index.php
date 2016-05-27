<?php

ini_set('error_reporting', -1);
ini_set('display_errors', 'On');

include 'CacheInterface.php';
include 'MultipleInterface.php';
include 'IncrementableInterface.php';
include 'RedisDriver.php';

use RedisDriver as Cache;

$redis = (new Redis());
$redis->connect('redis');
$cache = new Cache($redis);

// Any output from assert() calls is considered an error. We will inspect the buffer later
ob_start();

// Set
$res = $cache->set('buzz', 'bar', Cache::TTL_DAY);

// Get
$res = $cache->get('buzz');
assert($res === 'bar');

// Remove
$res = $cache->remove('buzz');
assert($res === true);

$res = $cache->remove('buzz');
assert($res === false);

// Clear
$res = $cache->clear();
assert($res === true);

// Multiple Set
$cacheData = [
    'foo' => 'value1',
    'bar' => 'value2',
    'baz' => 'value3'
];
$res = $cache->setMultiple($cacheData, 3); // 3 seconds
assert($res === true);

// Multiple Get
$res = $cache->getMultiple(['foo', 'not_defined_key']);
assert($res === ['foo' => 'value1']);

// Multiple Remove
$res = $cache->removeMultiple(['bar', 'not_defined_key']);
assert($res === ['bar' => true, 'not_defined_key' => false]);


// Multiple Remove - After initial set, 'foo' has expired.
sleep(3);
$res = $cache->removeMultiple(['foo']);
assert($res === ['foo' => false]);

// Increment
$res = $cache->set('num_users', 19);

$res = $cache->increment('num_users');
$res = $cache->get('num_users');
assert(intval($res) === 20);

$res = $cache->increment('num_users', 5);
$res = $cache->get('num_users');
assert(intval($res) === 25);

$res = $cache->decrement('num_users', 10);
$res = $cache->get('num_users');
assert(intval($res) === 15);

// Feature Detection
assert($cache->supports(Cache::SUPPORTS_MULTIPLE) === true);
assert($cache->supports(Cache::SUPPORTS_INCDEC) === true);
assert($cache->supports('non_existent_feature') === false);

// Check the output buffer
echo (ob_get_length() > 0) ? "UNEXPECTED OUTPUT DETECTED\n" : "ALL TESTS PASSED\n";
ob_end_flush();
