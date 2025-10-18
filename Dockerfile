# Utilise une image officielle PHP avec Apache intégré
FROM php:8.2-apache

# Définit le répertoire de travail (là où Apache sert les fichiers)
WORKDIR /var/www/html

# Copie tout le contenu du backend dans le conteneur
COPY . .

# Installe les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Configure Apache pour pointer vers le dossier api
RUN echo "DirectoryIndex index.php" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite

# Expose le port 80
EXPOSE 80

# Démarre Apache
CMD ["apache2-foreground"]