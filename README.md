# PSUH - Paris Saclay University Housing

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

Mise en place des outils d'aide au développement (`php-cs-fixer`):

```
chmod +x bin/pre-commit.sh
ln -s ../../bin/pre-commit.sh .git/hooks/pre-commit
cp -f .php-cs-fixer.dist.php .php-cs-fixer.php
```

Mise en place des variables d'environnement et de la base de données :
```
cp -f .env .env.local # variables à remplacer
docker-compose up -d database
```

Installation des dépendances et lancement du projet :
```
composer install
npm install
npm run build # ou npm run watch dans un autre terminal
symfony server:start
```

Le projet est ensuite accessible à cette URL : https://127.0.01:8000/


## Commandes utiles

* Exécuter les migrations :
```
php bin/console doctrine:migrations:migrate
```

* Générer une migration :
```
php bin/console make:migration
```

## Outils pour le développement

* Formatting automatique des fichiers de front-end (templates twig, js, scss, etc.) avec `prettier`
* Débugger php (avec Xdebug)
