# Utiliser une image PHP officielle
FROM php:7.3-cli

# Installer curl pour que cURL fonctionne dans les scripts PHP
RUN apt-get update && apt-get install -y curl

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Configuration du dossier de travail
WORKDIR /workspace

# Commande par défaut lorsque le container démarre
CMD ["php", "-a"]
