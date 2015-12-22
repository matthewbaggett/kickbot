FROM phusion/baseimage:latest
MAINTAINER Matthew Baggett <matthew@baggett.me>

CMD ["/sbin/my_init"]

# Install base packages
ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update && \
    apt-get -yq install \
        curl \
        git \
        apache2 \
        nodejs npm \
        nano \
        libapache2-mod-php5 \
        php5-mysql \
        php5-gd \
        php5-curl \
        php-pear \
        php5-dev \
        php-apc && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Add our crontab file
#ADD crons.conf /root/crons.conf

# Use the crontab file
#RUN crontab /root/crons.conf

RUN sed -i "s/variables_order.*/variables_order = \"EGPCS\"/g" /etc/php5/apache2/php.ini
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configure /app folder with sample app
RUN mkdir -p /app && rm -fr /var/www/html && ln -s /app/public /var/www/html
ADD . /app
ADD docker/ApacheSiteConf.conf /etc/apache2/sites-enabled/000-default.conf

# Run Composer
RUN cd /app && composer install

# Enable mod_rewrite
RUN a2enmod rewrite && /etc/init.d/apache2 restart

# Add ports.
EXPOSE 80

WORKDIR /app

# Add configs
ADD docker/apache2.conf /etc/apache2/apache2.conf

# Add startup scripts
RUN mkdir /etc/service/apache2
ADD docker/run.apache.sh /etc/service/apache2/run
ADD docker/run.kickbot.sh /etc/service/kickbot/run
RUN chmod +x /etc/service/*/run
