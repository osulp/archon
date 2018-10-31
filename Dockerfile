FROM php:5.3-apache

RUN apt-get update && \
    apt-get install -y php5-mysql php-xml-dtd php-xml-parser libxml2-dev

# Works only with PHP 5.4 and higher
# RUN docker-php-ext-install mysql xml

# Install MDB2, more info: http://archon.org/mdb2.html
RUN pear install MDB2-2.4.1
RUN pear install MDB2_Driver_mysql-1.4.1
