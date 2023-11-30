# PronoHub_backend

Assurez-vous d'avoir installé PHP, Composer et Symfony CLI avant de poursuivre.

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




