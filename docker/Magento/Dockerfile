FROM occitech/magento:php5.3-apache
MAINTAINER Occitech <contact@occitech.fr>

RUN yes | apt-get update \
    && apt-get install -y php5-xdebug libxml2-dev php-soap \
    && echo "xdebug.remote_enable=on" >> /etc/php5/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /etc/php5/conf.d/xdebug.ini \
    && echo "xdebug.max_nesting_level=500" >> /etc/php5/conf.d/xdebug.ini

RUN curl -o /usr/sbin/sendmail -L https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 \
    && chmod +x /usr/sbin/sendmail \
    && echo 'sendmail_path = "/usr/sbin/sendmail --smtp-addr=mail:1025 "' >> /etc/php5/apache2/php.ini
