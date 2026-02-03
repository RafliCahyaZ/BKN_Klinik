FROM dunglas/franken:php8.3

ENV SERVER_NAME=":80"

WORKDIR /app

COPY . /app

RUN apt update && apt install zip libzip-dev -y &&\
   docker-php-ext-install zip \
   docker-php-ext-enable zip


COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

RUN composer install





# RUN apt-get update && apt-get install -y \
#     libzip-dev \
#     zip \
#     # git \
#     # curl \
#     # libonig-dev \
#     # libxml2-dev \
#     # unzip \
#     # && rm -rf /var/lib/apt/lists/*

# RUN a2enmod rewrite

# RUN docker-php-ext-install pdo_mysql zip
#     # zip \
#     # pdo \
#     # pdo_mysql \
#     # mbstring \
#     # exif \
#     # pcntl \
#     # bcmath \
#     # ctype \
#     # fileinfo \
#     # json \
#     # xml

# ENV APACHE_DOCUMENT_ROOT /var/www/html/public
# RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
# RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

# COPY . var/www/html
# WORKDIR /var/www/html


