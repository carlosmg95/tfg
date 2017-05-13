FROM php:7.0-apache

RUN apt-get update
RUN apt-get install -y libssl-dev
RUN pecl install mongodb
RUN echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongo.ini
RUN echo "display_errors=Off" > /usr/local/etc/php/conf.d/php.ini

ENV MONGO_HOST "127.0.0.1"
ENV MONGO_DB "applicationdb"
ENV MONGO_PORT "27017"
ENV MONGO_USER "client"
ENV MONGO_PASS "gsimongodb2015"

ADD www /var/www/html/