FROM php:8.3-apache

# Install MySQL/PDO support
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache rewrite
RUN a2enmod rewrite

# Set LavaLust public folder as document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf

WORKDIR /var/www/html

COPY . .

# Give Apache permission to write to the application
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html

# Allow LavaLust .htaccess rules
RUN printf '%s\n' \
    '<Directory /var/www/html/public>' \
    '    AllowOverride All' \
    '    Require all granted' \
    '</Directory>' \
    > /etc/apache2/conf-available/lavalust.conf \
    && a2enconf lavalust

EXPOSE 80

CMD ["apache2-foreground"]
