##  **Installation du projet**
### **Cloner le projet**

### **Créer la base de donnée**

### **Dupliquer le fichier .env.example et modifier les variables**
```
APP_SECRET=
DATABASE_URL=
JWT_PASSPHRASE=
```

### **Dupliquer le fichier _docker_dev/.env.example et modifier les variables**

### **Ajouter les clés jwt**
```
config/jwt/private.pem
config/jwt/public.pem
```

##  **Jouer les commandes suivantes**
```
cd _docker_dev/
```

rendre helper.sh éxécutable et passer en LF si besoin

```
chmod +x helper.sh
```

### **Créer les images et lancer les conteneurs**
```
./helper.sh up
```

### **Installer les dépendances**
```
./helper.sh composer install
```

### **Générer les données en BDD**
```
./helper.sh symfony doctrine:migrations:migrate
```
```
./helper.sh symfony doctrine:fixtures:load
```
