Alexandre LUIS : Ultimaterror

# Setup
1) Faire une copie du .env nommée .env.local

cp .env .env.local

2) Générer l'APP_SECRET puis copier le code obtenu dans le .env.local

openssl rand -hex 32

3) Configurer l'accès à la BDD en mettant vos propres informations dans le .env.local

DATABASE_URL="mysql://username:password@127.0.0.1:3306/db_name?serverVersion=8.0.40&charset=utf8

4) Installer les dépendances du projet

composer install

5) Créer la BDD

php bin/console d:d:c

6) Charger les migrations dans la BDD

php bin/console d:m:m

7) Ajout des fixtures pour un jeu de données

php bin/console d:f:l

8) Générer les clés JsonWebToken (penser à déplacer les valeurs du .env dans le .env.local)

php bin/console lexik:jwt:generate-keypair

9) Lancer le serveur

symfony serve --no-tls

10) Aller sur le Swagger

localhost:8000/api

# Fonctionnalités
## Contexte
API qui gère des articles, des catégories et des utilisateurs
Outil principal : API Platform

## Contrôle d'accès
Mise en place des firewalls avec des routes en accès libre ou avec le jwt obligatoire sur d'autres
Utilisation des rôles pour filter l'accès à certaines routes

## Validation
Mise en place des validateurs Symfony comme NotBlank (pas null ou '') ou UniqueEntity (champ unique en BDD)

## Normalization & Denormalization
Définir quelles données entrent et lesquelles sortent

## Fixtures
Jeu de données pour tester l'application

## Relations entre les entités
Une catégorie peut avoir plusieurs articles et un article a une seule catégorie
Un utilisateur peut avoir plusieurs articles et un article a un seul auteur

## Process
Mise en place de process pour hasher le mit de passe et automatiquement ajouter l'auteur d'un article
### Erreur
Les process renvoir null et cela crèe des erreus. Je ne comprends pas pourquoi.

## La suite
Par manque de temps libre, je n'ai pas pu faire tout ce que je voulais sur ce projet.
Vous trouverez toutes mes idées dans TODO.md