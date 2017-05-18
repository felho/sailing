FROM tutum/apache-php

RUN apt-get update && apt-get install -yq git php5-mcrypt && rm -rf /var/lib/apt/lists/*
RUN rm -rf /app
RUN rm -rf /var/www/html
RUN a2enmod rewrite
RUN php5enmod mcrypt
COPY ./docker/vhost.conf /etc/apache2/sites-enabled/000-default.conf

ADD . /var/www
WORKDIR /var/www

RUN mkdir -p /var/www/storage/logs
RUN mkdir -p /var/www/bootstrap/cache
RUN chown -R www-data:www-data /var/www

USER www-data
RUN composer install
USER root