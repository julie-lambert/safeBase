# ✅ Étape 1 : Base PHP avec extensions nécessaires
FROM php:8.2-cli

# ✅ Installer les extensions PHP nécessaires à Laravel + client MySQL
RUN apt-get update && apt-get install -y \
    unzip git libzip-dev libpng-dev libonig-dev libxml2-dev \
    default-mysql-client \
    && docker-php-ext-install pdo_mysql zip bcmath


# ✅ Installer Composer globalement
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ✅ Définir le dossier de travail
WORKDIR /var/www/html

# ✅ Copier uniquement composer.json & composer.lock d’abord (cache build)
COPY composer.json composer.lock ./

# ✅ Installer dépendances PHP (sans scripts pour gagner du temps)
RUN composer install --no-interaction --prefer-dist --no-scripts --no-dev

# ✅ Copier le reste du projet
COPY . .

# ✅ Lancer une 2ème installation complète (pour autoload et scripts artisan)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# ✅ Donner les droits pour Laravel
RUN chmod -R 777 storage bootstrap/cache

# ✅ Commande par défaut : lancer Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

