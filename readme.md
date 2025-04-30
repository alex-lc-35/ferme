#  Ferme de la Rougeraie - Backend

Backend du projet **Ferme de la Rougeraie**, développé avec **Symfony 7** et **MySQL**.

---

## ✅ **Pré-requis**
- **Docker Desktop** 
- **PHP 8.2+** 
- **Composer 2+** 
- **Symfony CLI (optionnel)** 

---

##  **Installation du projet**
### 1️⃣ **Cloner le projet**
```sh
git clone https://github.com/votre-repo/ferme_de_la_rougeraie_v2-symfony-backend.git
cd ferme_de_la_rougeraie_v2-symfony-backend
```
### 2️⃣ **Installer Symfony CLI (optionnel)**
```sh
https://symfony.com/download
```

### 3️⃣ **Installer les dépendances PHP**
```sh
composer install
```
### 4️⃣ **Configurer les variables d’environnement + Générer les clés JWT (authentification) **
```sh
cp .env.example .env
Modifier le fichier .env avec vos propres valeurs sensibles
```
Installer OpenSSL for Windows
Ajouter le dossier bin d'OpenSSL à la variable d’environnement PATH
```sh
mkdir -p config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```
### 5️⃣ **Créer la base de données**
```sh
php bin/console doctrine:database:create
```

### 6️⃣ **Exécuter les migrations**
```sh
php bin/console doctrine:migrations:migrate
```

### 7️⃣ **Charger les fixtures**
```sh
php bin/console doctrine:fixtures:load
```

### 8️⃣ **Lancer le serveur (avec symfony CLI ou le serveur web de PHP )**
```sh   
symfony server:start

symfony serve

php -S 127.0.0.1:8000 -t public
```
### 9️⃣ **Accéder à l'application**
Ouvrir votre navigateur et accéder à l'URL suivante : 
``` sh
 http://127.0.0.1:8000/login
 http://127.0.0.1:8000/admin