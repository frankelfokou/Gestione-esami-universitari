FROM nginx:alpine

RUN rm /etc/nginx/conf.d/default.conf

# Copia i file statici del frontend nel container
COPY . /usr/share/nginx/html
