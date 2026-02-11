FROM postgres:15-alpine

# Copia lo script di inizializzazione nella cartella speciale di Postgres
# Tutto ciò che è qui viene eseguito al primo avvio
COPY init.sql /docker-entrypoint-initdb.d/01_init.sql
COPY seed.sql /docker-entrypoint-initdb.d/02_seed.sql
