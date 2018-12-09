FROM php:7.1

# facebook/webdriverを使用するのにzipが必要
RUN apt-get update && apt-get install -y zlib1g-dev
RUN docker-php-ext-install zip
