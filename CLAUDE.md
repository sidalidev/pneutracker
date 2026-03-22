# PneuTracker — CLAUDE.md

## Projet

Application de gestion de rendez-vous pour un reseau de centres de montage pneus.
Projet de formation pour apprendre Claude Code — les bugs sont **intentionnels**.

## Stack

- **Backend** : PHP 8.3 / Symfony 7.2 / Doctrine ORM 3.x / PHPUnit 11
- **Frontend** : React 19 / Vite 6
- **Base de donnees** : SQLite (dev) / PostgreSQL (prod)
- **Style PHP** : PSR-12
- **Style JS** : Pas de semicolons, trailing commas, imports explicites

## Structure

```
api/          ← Symfony 7.2 API JSON (pas de Twig, pas de SSR)
web/          ← React SPA (Vite), consomme l'API via /api
```

## Commandes

### Backend (api/)

```bash
composer install                              # Installer les dependances
php bin/console doctrine:migrations:migrate   # Appliquer les migrations
php bin/console doctrine:fixtures:load        # Charger les donnees de test
symfony serve                                 # Lancer le serveur dev (port 8000)
php bin/phpunit                               # Lancer les tests (2 sont VOLONTAIREMENT casses)
```

### Frontend (web/)

```bash
npm install       # Installer les dependances
npm run dev       # Serveur dev Vite (port 5173, proxy /api → localhost:8000)
npm run build     # Build de production
```

## Conventions

### PHP (api/)

- **PSR-12** strict — pas de discussion
- Controlleurs invocables quand ils n'ont qu'une action (`__invoke`)
- Entites Doctrine avec attributs PHP 8 (`#[ORM\Column]`)
- Repositories en injection de dependance, jamais `$em->getRepository()`
- Types stricts : `declare(strict_types=1)` partout
- Nommage : camelCase pour les methodes, PascalCase pour les classes

### JavaScript (web/)

- **Pas de semicolons** — jamais
- **Trailing commas** — toujours
- Composants React en fonctions (pas de classes)
- Un composant par fichier
- Imports explicites (pas de barrel exports)
- Comments en francais

## Fichiers a ne JAMAIS modifier

- `config/packages/security.yaml` — config securite validee par l'equipe infra
- `migrations/*.php` — migrations deja appliquees en production
- `.claude/settings.json` — gouvernance equipe

## Architecture API

```
POST   /api/appointments           → Creer un rendez-vous
GET    /api/appointments           → Lister (filtre ?franchise=)
GET    /api/appointments/{id}      → Detail d'un rendez-vous
PATCH  /api/appointments/{id}/status → Changer le statut
GET    /api/dashboard              → Statistiques du jour
```

### Statuts d'un rendez-vous

`pending` → `confirmed` → `completed`
                        → `cancelled` (depuis n'importe quel statut)

### Franchises

3 centres : `Nantes Centre`, `Rennes Nord`, `Brest Ocean`

## Tests

```bash
cd api && php bin/phpunit
```

**2 tests sont INTENTIONNELLEMENT casses** — c'est un exercice de formation :

1. `testCreateAppointment` — attend un mauvais code HTTP
2. `testCreateAppointmentInvalidPhone` — mock mal configure

Les trouver et les corriger fait partie du Module 05.

## Bugs plantes (formation)

Le code contient des bugs subtils plantes expres pour les exercices :

- **AppointmentController::create()** — pas de validation du format telephone → crash avec un numero invalide
- **DashboardController** — compte les rendez-vous annules dans le total du jour → chiffres faux
- **AppointmentRepository::countByStatus()** — n'exclut pas les annules du total general

Ces bugs sont la pour que les stagiaires apprennent a utiliser Claude Code pour les detecter et les corriger.

## Regles d'equipe

- Toute modification de `security.yaml` necessite une review de l'equipe infra
- Les migrations sont generees par Doctrine, jamais ecrites a la main
- Les tests doivent passer avant chaque merge (sauf les 2 casses exprès)
- Pas de `dd()` ou `dump()` qui trainent dans le code commite
