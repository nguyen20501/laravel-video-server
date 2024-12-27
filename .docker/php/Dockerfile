FROM  php:8.3-fpm

# Cài đặt các extension cần thiết
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mbstring zip pdo pdo_mysql

# Cài Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Copy custom php.ini configuration
COPY ./.docker/php/php.ini /usr/local/etc/php/conf.d/

# Other PHP configurations
COPY ./.docker/php/xdebug.ini /usr/local/etc/php/conf.d/

# Expose port for Xdebug (optional, depending on IDE configuration)
EXPOSE 9000

# Thiết lập thư mục làm việc
WORKDIR /var/www/html
