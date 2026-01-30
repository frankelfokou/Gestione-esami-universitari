FROM php:8.2-fpm-alpine

# Installa dipendenze per Postgres
RUN set -ex \
  && apk --no-cache add \
    postgresql-dev \
  && docker-php-ext-install pdo pdo_pgsql

# Imposta la cartella di lavoro
WORKDIR /var/www/html

# Copia il codice sorgente (opzionale se usi i volumi in dev, ma buono per prod)
COPY . .

# Permessi
RUN chown -R www-data:www-data /var/www/html

CMD ["php-fpm"]
