FROM php:8.2-cli

# ✅ Mettre à jour les paquets système
RUN apt-get update && apt-get upgrade -y && apt-get clean

# ✅ Installer extensions PHP requises
RUN docker-php-ext-install pdo pdo_mysql

# ✅ Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ✅ Dossier de travail
WORKDIR /var/www/html

# ✅ Copier projet
COPY . .

# ✅ Installer dépendances Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# ✅ Donner les droits
RUN chmod -R 777 storage bootstrap/cache
