FROM occitech/magento:php7.0-apache
MAINTAINER Occitech <contact@occitech.fr>

ENV APACHE_LOG_DIR ../../var/log/apache2

RUN curl -o /usr/sbin/sendmail -L https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 \
    && chmod +x /usr/sbin/sendmail \
    && echo 'sendmail_path = "/usr/sbin/sendmail --smtp-addr=mail:1025 "' >> $PHP_INI_DIR/conf.d/mail.ini

RUN apt-get update \
    && apt-get install -y libxml2-dev \
    && docker-php-ext-install soap

# Install PECL extensions
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug && echo "xdebug.remote_enable=on" >>  $PHP_INI_DIR/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> $PHP_INI_DIR/conf.d/xdebug.ini \
    && echo "xdebug.max_nesting_level=500" >> $PHP_INI_DIR/conf.d/xdebug.ini
