FROM richarvey/nginx-php-fpm:2.0.4

COPY . .

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_NAME=Libeyondea
ENV APP_ENV=production
ENV APP_KEY=base64:2Yilj/Kaoptbd4do1CaCOw7N2yFO5LODm6O7s87J5e0=
ENV APP_DEBUG=false
ENV APP_URL=https://server.libeyondea.co

ENV IMAGE_URL=https://server.libeyondea.co/images
ENV IMAGE_FOLDER=images

ENV LOG_CHANNEL=stderr
ENV LOG_DEPRECATIONS_CHANNEL=null
ENV LOG_LEVEL=debug

ENV DB_CONNECTION=mysql
ENV DB_HOST=bi7jkvhxjt2jzlvafkts-mysql.services.clever-cloud.com
ENV DB_PORT=3306
ENV DB_DATABASE=bi7jkvhxjt2jzlvafkts
ENV DB_USERNAME=uhbkyz2ikku0mova
ENV DB_PASSWORD=CtySv9pDaBAPelpY60cf

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version
RUN composer update --no-dev --working-dir=/var/www/html

RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
RUN php artisan migrate:fresh --seed --force
