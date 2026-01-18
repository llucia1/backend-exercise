FROM php:8.2-fpm-bullseye

ARG DEBIAN_FRONTEND=noninteractive
LABEL maintainer="llucia1@hotmail.com"


RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libicu-dev \
  && rm -rf /var/lib/apt/lists/*


  RUN docker-php-ext-install \
    intl \
    opcache \
    zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer


RUN echo "date.timezone=Europe/Madrid" > /usr/local/etc/php/conf.d/timezone.ini

# Nginx config + entrypoint
COPY nginx-site.conf /etc/nginx/sites-enabled/default
COPY entrypoint.sh /etc/entrypoint.sh
RUN chmod +x /etc/entrypoint.sh

WORKDIR /var/www
EXPOSE 80

ENTRYPOINT ["/etc/entrypoint.sh"]

