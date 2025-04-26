#  Ferme de la Rougeraie - Backend

Backend du projet **Ferme de la Rougeraie**, développé avec **Symfony 7** et **MySQL dans Docker**.

---

## ✅ **Pré-requis**
- **Docker Desktop** 
- **PHP 8.2+** 
- **Composer 2+** 
- **Symfony CLI** 

---

##  **Installation du projet**
### 1️⃣ **Cloner le projet**
```sh
git clone https://github.com/votre-repo/ferme_de_la_rougeraie_v2-symfony-backend.git
cd ferme_de_la_rougeraie_v2-symfony-backend
```
### 2️⃣ **Installer les dépendances PHP**
```sh
composer install
```

### 3️⃣ **Installer les dépendances PHP**
```sh
composer install

```
### 4️⃣ **Créer la base de données**
```sh
php bin/console doctrine:database:create
```

### 5️⃣ **Exécuter les migrations**
```sh
php bin/console doctrine:migrations:migrate
```

### 6️⃣ **Charger les fixtures**
```sh
php bin/console doctrine:fixtures:load
```

### 7️⃣ **Lancer le serveur**
```sh   
symfony server:start
```
### 8️⃣ **Accéder à l'application**
Ouvrir votre navigateur et accéder à l'URL suivante : 
``` sh
 http://127.0.0.1:8000/login
