FROM php:8.1-apache

# Copia todos os arquivos do teu projeto para a pasta do Apache no container
COPY . /var/www/html/

# Expõe a porta 80 para o Render
EXPOSE 80
