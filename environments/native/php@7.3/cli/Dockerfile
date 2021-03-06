FROM php:7.3-cli-alpine

LABEL maintainer="Grégory Planchat <gregory@kiboko.fr>"

ARG APP_UID=1000
ARG APP_GID=1000
ARG APP_USERNAME=docker
ARG APP_GROUPNAME=docker

RUN set -ex \
    && addgroup -g ${APP_GID} ${APP_USERNAME} \
    && adduser -u ${APP_UID} -h /opt/${APP_USERNAME} -H -G ${APP_GROUPNAME} -s /sbin/nologin -D ${APP_USERNAME} \
    && apk update \
    && apk upgrade \
    && apk add \
        wget \
        ca-certificates \
        git \
        nodejs \
        npm \
        patch \
    && update-ca-certificates \
    && apk add --virtual .build-deps $PHPIZE_DEPS \
    && docker-php-ext-install opcache \
    && apk add --update icu-dev icu \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && apk del icu-dev \
    && apk add gnu-libiconv --update-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted \
    && apk del \
        .build-deps \
        gdbm \
        gmp \
        isl \
        libatomic \
        libbz2 \
        libffi \
        libgcc \
        libgomp \
        libldap \
        libltdl \
        libmagic \
        libtool \
        mpc1 \
        musl-dev \
        perl \
        pkgconf \
        pkgconfig \
        python3 \
        re2c \
        readline \
        sqlite-libs \
    && rm -rf /tmp/* /var/cache/apk/* \
    && EXPECTED_SIGNATURE="$(wget -q -O - https://composer.github.io/installer.sig)" \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && ACTUAL_SIGNATURE="$(php -r "echo hash_file('sha384', 'composer-setup.php');")" \
    && if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]; then >&2 echo 'ERROR: Invalid installer signature'; rm composer-setup.php; exit 1; fi \
    && php composer-setup.php --install-dir /usr/local/bin --filename composer \
    && php -r "unlink('composer-setup.php');" \
    && mkdir -p /opt/${APP_USERNAME}/.npm \
    && mkdir -p /opt/${APP_USERNAME}/.composer \
    && chown ${APP_USERNAME}:${APP_GROUPNAME} /opt/docker/.npm \
    && composer global init \
    && composer global require \
        friendsofphp/php-cs-fixer \
        phpunit/phpunit:^8.5 \
        phpspec/phpspec \
        infection/infection \
    && chown -R ${APP_USERNAME}:${APP_GROUPNAME} /opt/${APP_USERNAME}

ENV PATH="/opt/${APP_USERNAME}/.composer/vendor/bin:${PATH}"

ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

COPY config/memory.ini /usr/local/etc/php/conf.d/memory.ini

WORKDIR /var/www/html
