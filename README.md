Find your container that's been launched

``` bash
docker ps
```

Run the index.php script inside the container

Successful output
``` bash
docker exec -ti psrsimplecache_application_1 php -f index.php
ALL TESTS PASSED
```

Error output
``` bash
docker exec -ti psrsimplecache_application_1 php -f index.php

Warning: assert(): assert($res === false) failed in /var/www/index.php on line 57
UNEXPECTED OUTPUT DETECTED
```
