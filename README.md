
![Logo](https://cdn-assets-eu.frontify.com/s3/frontify-enterprise-files-eu/eyJwYXRoIjoic3VwZXJjZWxsXC9maWxlXC9wS0JNWXdhOW51aEFnRG5wSnkzdi5wbmcifQ:supercell:QirzHVs-XdUZ6nYY3QARLhNRZ5ImTEhgF_lY0ehWTmE?width=2400)


# Brawlstar Custom

Une application web permettant de retrouver les Brawlers avec leurs détails, d'afficher les statistiques des joueurs et de créer des équipes sur Brawl Stars.

## Variables d'environnement

Modifier la clé API créer sur le site officiel de [Brawlstars](https://developer.brawlstars.com/#/)

`BRAWLSTARS_API_TOKEN=`

Modifier les accès MySQL

`DATABASE_URL='mysql://root:password@127.0.0.1:3306/brawlstar?serverVersion=8.0'`


## Installation

Cloner le projet
```git
  git clone https://github.com/Hrivaux/brawlstar-custom
```

Récupérer les dépendances via composer 

```bash
  composer install
```

Créer la base de données et appliquer les migrations

```bash
php bin/console doctrine:migrations:migrate
```

    
## Commandes utiles

Créer un compte administrateur automatiquement 

```bash
php bin/console app:create-admin
```


## Auteurs

- [@Rayan69100](https://www.github.com/Rayan69100)
- [@maxousql](https://www.github.com/maxousql)
- [@Hrivaux](https://www.github.com/Hrivaux)

