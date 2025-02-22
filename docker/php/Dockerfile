FROM php:8.4-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    wget \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Rewrite mode apache
RUN a2enmod rewrite
RUN service apache2 restart

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Configurer git 
RUN git config --global user.name "John Doe" \
    && git config --global user.email johndoe@example.com

WORKDIR /var/www/