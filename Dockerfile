# PROJECT 11 - Sistem Informasi Geografis BPN (PHP Native)
FROM php:8.3-apache

# Install required system packages
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    curl \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip

# Enable Apache modules
RUN a2enmod rewrite headers

# Fix ServerName warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set document root to /var/www/html
ENV APACHE_DOCUMENT_ROOT /var/www/html

# Update Apache configuration
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Create directory and set permissions
RUN mkdir -p /var/www/html

# Copy application files
COPY . /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \; && \
    chmod 666 /var/www/html/data/offices.json && \
    chmod 755 /var/www/html/includes /var/www/html/config /var/www/html/assets

# Expose port 80
EXPOSE 80

CMD ["apache2-foreground"]