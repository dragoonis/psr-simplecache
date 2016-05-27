FROM php:7.0.6-fpm-alpine

ENV PHPREDIS_VERSION 2.2.7


# Install PHP extensions
#RUN curl -L -o /tmp/redis.tar.gz https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz \
RUN curl -L -o /tmp/redis.tar.gz https://github.com/dragoonis/phpredis/archive/3.0.tar.gz \
    && tar xfz /tmp/redis.tar.gz \
	&& rm -r /tmp/redis.tar.gz \
	#&& mv phpredis-$PHPREDIS_VERSION /usr/src/php/ext/redis \
    && mv phpredis-3.0 /usr/src/php/ext/redis \

	&& docker-php-ext-install redis

WORKDIR /var/www

EXPOSE 9000
CMD ["php-fpm"]
