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

## Création des équipes

1. Aller sur l'url 
```bash
    /dashboard/createTeam
 ```

## Création des matches

1. Aller sur l'url 
```bash
    /dashboard/app
 ```
2. Pouvez changer le round du championnat afin d'obtenir les matchs déroulés et les prohains matchs
  Dans ApiController.php
```bash
    #[Route('/dashboard/app', name: 'app_api_app')]
    public function fetchAndStoreEvents()
    {
        $leagueId = '4328';

        $currentYear = date('Y');
        $nextYear = $currentYear + 1;

        $seasonYear = $currentYear . '-' . $nextYear;

        $round = 10;

        $apiEndpoint = "https://www.thesportsdb.com/api/v1/json/3/eventsround.php?id={$leagueId}&r={$round}&s={$seasonYear}";
 ```
