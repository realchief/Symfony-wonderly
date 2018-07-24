FROM php:7.0-fpm-alpine

# Install php extensions
RUN apk update && apk add --no-cache --virtual .build-deps autoconf gcc make g++ \
    && pecl install xdebug-2.5.0 \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable xdebug \
    && rm -rf /var/cache/apk/* \
    && apk del .build-deps && rm -rf tmp/*

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/bin/composer \
    && chmod +x /usr/bin/composer

# Configure fpm
RUN echo "xdebug.remote_enable=on" >> /usr/local/etc/php/php.ini \
    && echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/php.ini \
    && echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/php.ini \
    && echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/php.ini \
    && echo "xdebug.max_nesting_level=1000" >> /usr/local/etc/php/php.ini \
    && echo "xdebug.remote_port=9000" >> /usr/local/etc/php/php.ini

EXPOSE 9000
