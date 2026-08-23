FROM php:8.4-cli
RUN apt-get update && apt-get install -y git curl unzip libpng-dev libonig-dev libxml2-dev libzip-dev sqlite3 libsqlite3-dev libpq-dev && docker-php-ext-install pdo pdo_sqlite pdo_pgsql mbstring exif pcntl bcmath gd zip && rm -rf /var/lib/apt/lists/*
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build
RUN composer dump-autoload --optimize
RUN chmod -R 775 storage bootstrap/cache
COPY start.sh /start.sh
RUN chmod +x /start.sh
EXPOSE 10000
CMD ["/start.sh"]
