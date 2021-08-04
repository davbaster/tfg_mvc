FROM php:8.0.7-apache-buster

# copy apache2.conf custom file
# COPY apache2.conf /etc/apache2

# Install MariaDB client
RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
    && apt-get install -y mariadb-client \ 
    && apt-get clean -y && rm -rf /var/lib/apt/lists/*

# install pdo extentions
RUN docker-php-ext-install mysqli pdo pdo_mysql

#install xdebug
#RUN apt install php-dev
RUN pecl install xdebug && docker-php-ext-enable xdebug \

#the container dir where php is installed is /usr/local/lib/php
#not yet in linux: xdebug.remote_host = host.docker.internal \n\
#18 and 19 works as long as they are enabled by ufw
#xdebug.remote_host sets the address of our container
    && echo "\n\
    xdebug.remote_host = 172.17.0.1 \n\
    xdebug.default_enable = 1 \n\
    xdebug.remote_autostart = 1 \n\
    xdebug.remote_connect_back = 0 \n\
    xdebug.remote_enable = 1 \n\
    xdebug.remote_handler = "dbpg" \n\
    xdebug.remote_port = 9003 \n\
    xdebug.remote_log = /www/xdebug.log \n\
    " >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

#copying php.ini file to /usr/local/etc/php/conf.d
COPY php.ini "$PHP_INI_DIR/php.ini"

# Update args in docker-compose.yaml to set the UID/GID of the "vscode" user.
#ARG USER_UID=1000
#ARG USER_GID=$USER_UID
#RUN if [ "$USER_GID" != "1000" ] || [ "$USER_UID" != "1000" ]; then groupmod --gid $USER_GID vscode && usermod --uid $USER_UID --gid $USER_GID vscode; fi

# [Optional] Uncomment this section to install additional OS packages.
# RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
#     && apt-get -y install --no-install-recommends docker-php-ext-install pdo pdo_mysql


#creates /www folder and link /
RUN mkdir /www && \
  ln -s /www /var/www/html/

#giving write permissions to folder
RUN chown -R www-data:www-data /var/www/html/

#giving write permissions to folder - use when production
#RUN chown -R www-data:www-data /www/public/

#apache complements 
RUN a2enmod rewrite headers

# Starts apache server
CMD ["apache2-foreground"]

#service available
EXPOSE 80