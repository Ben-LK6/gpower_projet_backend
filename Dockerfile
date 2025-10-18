# Utilise une image officielle PHP avec Apache intégré
FROM php:8.2-apache

# Définit le répertoire de travail (là où Apache sert les fichiers)
WORKDIR /var/www/html

# Copie tout le contenu de ton projet dans le conteneur
COPY . .

# Installe les extensions PHP nécessaires (pdo_mysql pour MySQL par exemple)
RUN docker-php-ext-install pdo pdo_mysql

# Expose le port 80 pour que Render puisse accéder à ton serveur web
EXPOSE 80

# Démarre Apache quand le conteneur s'exécute
CMD ["apache2-foreground"]
