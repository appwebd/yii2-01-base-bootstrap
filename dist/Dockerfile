FROM php:7.1-fpm
ARG CREDENTIALS_GIT
ARG REPO=git.gob.cl/chileatiende/chileatiende
ARG DIRECTORY_PROJECT=/home/pro/repository/yii2-base/dist

WORKDIR $DIRECTORY_PROJECT

# Install Packages
RUN apt-get update && apt-get install -y \
  libxml2-dev git zip unzip \
  zlib1g-dev libpng-dev \
  --no-install-recommends

  # Docker extension install
RUN  docker-php-ext-install \
  opcache \
  pdo_mysql \
  pdo \
  mbstring \
  tokenizer \
  xml \
  ctype \
  json \
  zip \
  gd \
  bcmath \
  soap

  # error to stderr php-fpm
RUN echo -e "\
  log_errors = On\n \
  error_log = /dev/stderr\n \
  error_reporting = E_ALL\n \
  memory_limit = 256M" \
  > /usr/local/etc/php/php.ini

  # Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clone repository
COPY . $DIRECTORY_PROJECT

# Install dependencies  from project
RUN composer install \
  && composer clearcache \
  && rm -rf /root/.composer /usr/local/bin/composer

RUN chown -R www-data:www-data storage/

RUN apt-get remove --purge -y git curl \
  && apt-get autoremove -y \
  && apt-get autoclean

ENV LANG es_CL.UTF-8
ENV LANGUAGE es_CL:es
ENV LC_ALL es_CL.UTF-8
ENV TZ America/Santiago

RUN echo "APP_KEY=$(php artisan key:generate --show)" > .env

EXPOSE 9000
CMD ["php-fpm"]
