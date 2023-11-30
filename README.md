# PronoHub_backend

Assurez-vous d'avoir installé PHP, Composer et Symfony CLI avant de poursuivre.
## Ordre d'installation des différentes parties du projet

- Back Office
- API
- Front

## Ordre de Run

- API:
    ```bash
    php bin/console doctrine:schema:update --force
    ```
    ```bash
    symfony serve
    ```
- Back Office:
    ```bash
    php bin/console doctrine:schema:update --force
    ```
    ```bash
    symfony serve
    ```
- Front:
    ```bash
    npm install
    ```
    ```bash
    npm start
    ```
## Installation

1. Clonez le projet depuis le dépôt Git :

    ```bash
    git clone https://github.com/votre-utilisateur/votre-projet.git
    ```

2. Installez les dépendances avec Composer :

    ```bash
    cd votre-projet
    composer install
    ```

## Configuration de la base de données

2. Créez le fichier `.env.local` :
   
   Copiez le contenu de `.env` vers un nouveau fichier `.env.local` et mettez à jour la chaîne de connexion MySQL.

    ```bash
    cp .env .env.local
    ```

1. Créez la base de données et appliquez les migrations :

    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:schema:update --force
    ```

## Création d'un utilisateur administrateur

1. Utilisez la commande suivante pour créer un utilisateur administrateur :

    ```bash
    php bin/console app:create-admin-user
    ```

    Suivez les instructions pour définir le nom d'utilisateur, le mot de passe, etc.

## Création des championnats

1. Utilisez la commande pour créer des championnats :

    ```bash
    php bin/console app:add-league-api
    ```

## Création des matches

1. Se rendre sur l'url
 ```bash
dashboard/game/new
```
2. Renseigner l'id d'une ligue et le round
 ```bash
exemple:
// nom de la league : ID
Ligue 1 : 4334
Première league : 4328
top 14 : 4430
```

3. Renseignez l'année de la saison :
   - **Saison :** Certains championnats durent une année civile, tandis que d'autres peuvent avoir des saisons qui traversent deux années. Assurez-vous de spécifier correctement l'année de la saison en cours.
Accédez à la page d'administration des paris sportifs.

    ```bash
    dashboard/bets
    ```

## Gestion des validations de paris sportifs

1. Accédez à la page détail d'un match où sont répertoriés les paris :
    ```bash
    dashboard/game/{game_id}
    ```
   Remplacez `{game_id}` par l'id du match.

2. Sur cette page, vous pourrez visualiser tous les paris associés à ce jeu.

3. exemple : 

![image](https://github.com/NoahRazzaq/PronoHub_backend/assets/84766310/7395b920-95ab-4048-92cb-5e864e755618)


## Visualiser la liste des championnats

1. Accédez à la page répertoriant tous les championnats :
    ```bash
    dashboard/league/api
    ```
   Cette page présente une liste complète des championnats disponibles.

2. Vous pouvez explorer les détails de chaque championnat en cliquant sur les liens associés ou en suivant les actions disponibles sur cette page.


![image](https://github.com/NoahRazzaq/PronoHub_backend/assets/84766310/446c4ebb-bef3-4372-b5f5-52d06dc37d75)

## Voir tous les tours d'un championnat

1. Accédez à la page dédiée au championnat dont vous souhaitez voir tous les tours. Remplacez `{id}` par l'identifiant du championnat. Par exemple :
    ```bash
    dashboard/league/api/{id}
    ```
   Cette page affiche une vue détaillée du championnat, y compris tous les tours disponibles.

![image](https://github.com/NoahRazzaq/PronoHub_backend/assets/84766310/620a9f19-293f-44cf-ad5b-33c2e6efeb09)

2. Explorez les détails de chaque tour en cliquant sur les liens associés.
![image](https://github.com/NoahRazzaq/PronoHub_backend/assets/84766310/8d5978a2-f3a9-453d-86db-e7db8b22de19)


