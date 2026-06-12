# ImmoSearch

Application web Symfony 7 — plateforme de recherche immobilière (projet fil rouge CDA).

## Stack

- PHP 8.3 + Symfony 7.1 (full-stack, Twig)
- MariaDB 10.11 + Doctrine ORM
- Apache 2 (conteneur)
- Docker Compose

## Démarrage rapide (Docker)

```bash
# Construire et démarrer les conteneurs
docker compose up -d --build

# Installer les dépendances Composer
docker compose exec app composer install

# Créer le schéma BDD et charger les fixtures
docker compose exec app php bin/console doctrine:database:create --if-not-exists
docker compose exec app php bin/console doctrine:schema:update --force
docker compose exec app php bin/console doctrine:fixtures:load --no-interaction
```

L'application est ensuite accessible sur **http://localhost:8080**.

## Comptes de démonstration

| Rôle  | Email                  | Mot de passe |
|-------|------------------------|--------------|
| Admin | admin@immosearch.test  | admin1234    |
| Agent | agent@immosearch.test  | agent1234    |
| User  | user@immosearch.test   | user1234     |

## Architecture

Application full-stack Symfony, pattern MVC, architecture 3-tiers
(Navigateur → Apache/Symfony → MariaDB).

```
src/
├── Controller/      # Routes + orchestration HTTP
│   └── Admin/       # Back-office agent/admin
├── Entity/          # 8 entités Doctrine (MERISE)
├── Form/            # Types Symfony Form
├── Repository/      # Requêtes DQL (recherche, stats)
├── DataFixtures/    # Données de démonstration
└── Kernel.php
```

## Fonctionnalités MVP

- Recherche/filtrage de biens (type, ville, prix, surface, tri)
- Fiche détaillée d'un bien
- Inscription / connexion (Symfony Security, mots de passe hashés)
- Demande de visite (utilisateur authentifié)
- Back-office agent : CRUD biens, gestion des visites
- Tableau de bord avec statistiques

## Sécurité

- Mots de passe hashés (`auto` = bcrypt/argon2)
- Protection CSRF native Symfony sur les formulaires et actions destructives
- Échappement automatique Twig (XSS)
- Requêtes paramétrées via Doctrine (injection SQL)
- Hiérarchie de rôles : `ROLE_USER` ⊂ `ROLE_AGENT` ⊂ `ROLE_ADMIN`
- En-têtes HTTP sécurisés (X-Frame-Options, X-Content-Type-Options) via `.htaccess`

## Tests

```bash
docker compose exec app vendor/bin/phpunit
```

Tests unitaires (entités) + fonctionnels (contrôleurs WebTestCase).
