ARG PHP_IMAGE_APP_VERSION="fpm-alpine3.15"

FROM "php:${PHP_IMAGE_APP_VERSION}" AS base
RUN apk update
RUN apk add gcc autoconf icu-dev yaml-dev
RUN apk add --no-cache --virtual .phpize-deps ${PHPIZE_DEPS}
RUN pecl install -o -f ds pcntl posix yaml
RUN docker-php-ext-configure intl
RUN docker-php-ext-install bcmath intl
RUN docker-php-ext-enable opcache ds bcmath yaml intl


FROM base AS shrikeh
ARG LOCAL_BIN_PATH=/usr/local/bin
ENV APP_ENV=dev

COPY --from=composer:latest /usr/bin/composer "${LOCAL_BIN_PATH}/composer"
RUN chmod +x "${LOCAL_BIN_PATH}/composer"

RUN apk add git openssh

WORKDIR /shrikeh

# Copy in only the files and directories needed for the app from the working directory
ADD ./.env ./
ADD ./composer.json ./
ADD ./composer.lock ./
ADD ./src ./src
ADD ./auth.json ./

RUN ${LOCAL_BIN_PATH}/composer install --no-dev

# Copy only the files from before, leaving before dependencies such as composer and git
FROM base AS app
ARG LOCAL_BIN_PATH=/usr/local/bin

COPY --from=composer:latest /usr/bin/composer "${LOCAL_BIN_PATH}/composer"
RUN chmod +x "${LOCAL_BIN_PATH}/composer"

COPY --from=shrikeh /root/.composer/ /root/.composer/

WORKDIR /cache
VOLUME /cache

RUN chown root:root /cache
RUN chmod 0777 /cache

WORKDIR /shrikeh
VOLUME /shrikeh
COPY --from=shrikeh /shrikeh /shrikeh

RUN composer validate
RUN chown -R www-data:www-data /shrikeh

FROM app AS dev
ENV XDEBUG_MODE=coverage
RUN apk add --update linux-headers
RUN pecl install xdebug
RUN apk add git
RUN docker-php-ext-enable xdebug
RUN ${LOCAL_BIN_PATH}/composer install
ENTRYPOINT ${LOCAL_BIN_PATH}/composer test
