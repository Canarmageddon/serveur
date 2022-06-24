# Mise en production du projet

## Prérequis


* Docker (Suivez les instructions sur la [documentation](https://docs.docker.com/get-docker/))
* Npm ([install](https://www.npmjs.com/package/npm))



## Application client

1. Récupérer l’application depuis [GIT](https://github.com/Canarmageddon/web/)
2. (*Facultatif*) Les fichiers nécessaires aux déploiement se trouvent dans le répertoire **deploy**
    * **default.conf** - configuration de serveur nginx (modifiez si besoin)
    * **.env** - Modifiez la valeur de host si besoin
3. Dans le répertoire racine du projet lancez la commande 
```
npm install && npm run build && mv build deploy/reactBuild
```
4. Allez dans le répertoire deploy et lancez 
```
sudo docker-compose up
```

L'application est maintenant accessible sur http://localhost:81
## API

<br>

L’application client doit être lancée au préalable. Le proxy nécessaire au déploiement de l’api se lance avec.

1. Récupérer l’application depuis [GIT](https://github.com/Canarmageddon/serveur)
2. (*Facultatif*) Pour plus de sécurité changez l’utilisateur et le mdp de la bdd dans le fichier **.bdd.env** et la variable `DATABASE_URL` dans **.env.local** qui se trouvent dans le répertoire **docker**
    * **.env** - Modifiez la valeur de host si besoin
    * **.env.local** - Mettez un string aléatoire à `APP_SECRET` pour plus de sécurité.
3. Copiez ces 2 fichiers dans la racine du projet. Remplacez le fichier **.env** dans la racine du projet par le **.env** dans le répertoire **docker**.
4. Lancez cette commande dans la racine du projet.
```
sudo docker-compose up
``` 


L'installation des dépendances peut prendre beaucoup de temps (jusqu'au 8 min) en fonction de votre système.

5. Dans l’autre terminal, exécutez la commande suivante pour ouvrir le shell du container 
```
docker-compose exec php sh
```
6. Puis exécutez les commandes suivantes:
    * Créé la base de données avec le nom écrit dans **.env.local**
    ```
    php bin/console d:d:c
    ```
    * Créé les tables dans la base de données depuis le modèle des entités de Symfony
    ```
    php bin/console d:s:c -f
    ```
    * Génère les clés pour les JWT
    ```
    php bin/console lexik:jwt:generate-keypair
    ```

L’api est maintenant accessible sur http://localhost:80/api

