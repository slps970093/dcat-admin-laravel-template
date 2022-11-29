FROM chialab/php-dev:8.1-apache

# change document root =>  https://stackoverflow.com/questions/51393494/how-to-change-the-document-root-in-php7-1-apache-from-docker-compose-yml
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
