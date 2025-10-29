# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
    </Directory>\n\
    </VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Copy application files
COPY . /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Create .env file if it doesn't exist
RUN if [ ! -f .env ]; then \
    if [ -f .env.example ]; then \
    cp .env.example .env; \
    else \
    echo "APP_NAME=TimeSheet" > .env; \
    echo "APP_ENV=production" >> .env; \
    echo "APP_DEBUG=false" >> .env; \
    echo "APP_URL=http://localhost" >> .env; \
    echo "DB_CONNECTION=sqlite" >> .env; \
    echo "DB_DATABASE=/var/www/html/database/database.sqlite" >> .env; \
    fi; \
    fi

# Generate application key
RUN php artisan key:generate

# Create SQLite database file
RUN touch database/database.sqlite

# Run migrations and seeders
RUN php artisan migrate --force || true \
    && php artisan db:seed --force || true



# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
