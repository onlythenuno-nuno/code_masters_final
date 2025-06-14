FROM php:8.1-apache

# Habilita mod_rewrite (opcional se usares .htaccess)
RUN a2enmod rewrite

# Copia teus arquivos pro Apache
COPY . /var/www/html/

# Render espera que o app escute na porta da variável de ambiente $PORT
ENV PORT 10000
EXPOSE 10000

# Configura o Apache pra escutar na porta correta
RUN sed -i "s/80/\${PORT}/g" /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf

CMD ["apache2-foreground"]
