FROM php:8.1.6-apache
RUN apt-get update
RUN apt-get install -y zip
RUN apt-get install -y libpng-dev zlib1g-dev
RUN apt-get install -y libzip-dev
RUN apt-get install -y wget git ; wget https://getcomposer.org/composer.phar
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-configure zip 
RUN docker-php-ext-install zip
RUN docker-php-ext-install gd
RUN php composer.phar require elasticsearch/elasticsearch
RUN wget https://jpgraph.net/download/download.php?p=49 -O /usr/local/jpgraph.tgz
RUN cd /usr/local/ ; tar -zxf jpgraph.tgz
RUN mv /var/www/html/vendor /usr/local/
