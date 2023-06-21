# CASA - Paris Saclay University Housing

## Prérequis

* Git
* Docker / docker-compose


* PHP (v8.1+)
* Symfony (v6.0+)
* Symfony CLI (v5.4.2+)
* Composer (v2.2.6+) [optionnel]


* Node (v16.14.0+)
* Npm (v8.5.2+)

## Installation en local

### Mise en place des outils d'aide au développement (optionnel)

Pour avoir `php-cs-fixer` en hook de pré-commit:

```
chmod +x bin/pre-commit.sh
ln -s ../../bin/pre-commit.sh .git/hooks/pre-commit
cp -f .php-cs-fixer.dist.php .php-cs-fixer.php
```

### Mise en place des variables d'environnement et de la base de données

```
cp -f .env .env.local # variables à remplacer
docker-compose up -d database
```

### Installation des dépendances
```
composer install
npm install
npm run build # ou npm run watch dans un autre terminal
```

### Mise en place du stockage objet pour l'upload de documents

```
docker-compose up -d minio
```

Se connecter ensuite sur `localhost:9091` (console minio) pour créer un bucket et un user (avec accès `read-write`). Il
faut ensuite reporter les valeurs associées dans son fichier d'environnement local.

### Lancement du projet en local

Avant de lancer l'application elle-même, il faut lancer (en plus des conteneurs de base de données et de stockage objet)
le serveur mjml (qui sert à mettre en forme les modèles d'emails), ainsi que le serveur mail (`mailhog`). On peut
ensuite lancer le serveur. Un profil `docker-compose` permet de sélectionner l'ensemble des services nécessaires :
`local`. Il inclut les deux précédemment cités ainsi que la base de données et le stockage objet.

Il faut donc lancer les commandes suivantes :

```
COMPOSE_PROFILES=local docker-compose --env-file=.env.local up -d
symfony server:start
```

Le projet est ensuite accessible à cette URL : http://127.0.01:8000/

Pour voir les mails envoyés par l'application sur l'interface de `mailhog` : http://127.0.0.1:8025

### GeoCoding API 

https://www.geoapify.com/

* Se connecter ( sso google) 
* Créer un projet 
* générer une API KEY

Ajouter la clé dans le .env.local 
```
GEOCODING_API_ID=
```

### Lancement des tests

Pour lancer les tests, il faut commencer par lancer la base de données de tests (via docker):

```
docker-compose up -d test-database
```

Il faut ensuite vérifier que les variables d'environnements indiquées dans le fichier `.env.test` permettent bien de se
connecter à cette base de données (variables `PASSWORD` et `DATABASE_URL`). Pour avoir une configuration locale
particulière, il est possible de copier le fichier `.env.test` dans `.env.test.local` (non commit) qui prendra la
précédence.

Il faut ensuite générer les tables dans cette base de données telles que décrites par les entités, pour cela :

```
php bin/console --env=test doctrine:schema:create
```

On peut enfin lancer les tests avec la commande suivante :

```
php bin/phpunit
```


## Commandes utiles

* Exécuter les migrations :
```
php bin/console doctrine:migrations:migrate
```

* Générer une migration :
```
php bin/console make:migration
```

* Chargement des données 

**ATTENTION** : cela vide la database , sauf si on ajoute --append , mais dans ce cas, cela créé des doublons
```
symfony console doctrine:fixtures:load 
```

## Outils pour le développement

* Formatting automatique des fichiers de front-end (templates twig, js, scss, etc.) avec `prettier`
* Débugger php (avec Xdebug)

# Assets sur Github

Les répertoires assets/images/guide et assets/images/home sont vides car les images du projet initial ne peuvent pas être diffusées en open source. 
