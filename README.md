
The docker environment and source files exist here: https://github.com/dragoonis/psr-simplecache

Spinning up the containers
``` bash
docker-compose up
```

Open a new tab. Find the `application` container name

``` bash
$ docker-compose ps
            Name                          Command               State              Ports
---------------------------------------------------------------------------------------------------
psrsimplecache_application_1   php-fpm                          Up      9000/tcp
psrsimplecache_nginx_1         nginx -g daemon off;             Up      443/tcp, 0.0.0.0:80->80/tcp
psrsimplecache_redis_1         docker-entrypoint.sh redis ...   Up      6379/tcp
```

SSH into the application container

``` bash
docker exec -ti psrsimplecache_application_1 sh
/var/www #
```

Execute the test suite

Run this command with output_buffering flag to force it to exist. By default php.ini it's off.
It will perform many assertions to prove, against a real redis instance, that it's functioning as expected.

``` bash
/var/www # php -d output_buffering=1 -f index.php
ALL TESTS PASSED
```

If any errors occur, you should see something like this
``` bash
Warning: assert(): assert($res === false) failed in /var/www/index.php on line 57
UNEXPECTED OUTPUT DETECTED
```
