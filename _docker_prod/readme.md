##  **Installation du projet**
### **Cloner le projet**

### **Créer la base de donnée**

### **Dupliquer le fichier .env.prod.example et modifier les variables**
```
APP_SECRET=
DATABASE_URL=
JWT_PASSPHRASE=
```

### **Dupliquer le fichier _docker_prod/.env.example et modifier les variables**

### **Ajouter les clés jwt**
```
config/jwt/private.pem
config/jwt/public.pem
```

##  **Jouer les commandes suivantes**
```
cd _docker_prod/
```

rendre helper.sh éxécutable et passer en LF si besoin

```
chmod +x helper.sh
```

### **Créer les images et lancer les conteneurs**
```
./helper.sh refresh
```

### **Donner les droits d'écriture pour les uploads**
```
sudo chown -R www-data:www-data public/uploads
```
