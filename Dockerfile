FROM php:7.3-apache-buster
LABEL maintainer="Mark Tompsett <mark_tompsett@sil.org>"

ENV REFRESHED_AT 2021-03-24

# Make sure apt has current list/updates
RUN apt-get update -y \
# Fix timezone stuff from hanging.
    && echo "Etc/UTC" > /etc/timezone \
    && apt-get install -y tzdata \
# Install necessary PHP building blocks
    && apt-get install -y zip unzip make curl wget \
# Clean up to reduce docker image size
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /data
WORKDIR /data
COPY ./ /data

RUN cd /data && ./composer-install.sh
RUN mv /data/composer.phar /usr/bin/composer
RUN cd /data/tests && ln -sf ../vendor/phpunit/phpunit/phpunit phpunit
