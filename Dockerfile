FROM php:7.2-fpm

RUN apt-get update
#RUN apt-get install -y curl
#RUN pecl install xdebug
#RUN docker-php-ext-enable xdebug
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 9000
CMD ["php-fpm"]