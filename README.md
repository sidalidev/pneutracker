# PneuTracker

Application de gestion de rendez-vous pour un reseau de centres de montage pneus.
Projet de formation Claude Code pour l'equipe Allo Pneus.

## Setup

### API (Symfony 7.2)

```bash
cd api
composer install
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
symfony serve
```

L'API tourne sur `http://localhost:8000`.

### Frontend (React + Vite)

```bash
cd web
npm install
npm run dev
```

Le frontend tourne sur `http://localhost:5173` avec proxy vers l'API.

### Tests

```bash
cd api
php bin/phpunit
```

> **Note** : 2 tests sont intentionnellement casses pour les exercices de formation.

## Structure

```
api/   ← Symfony 7.2 API JSON
web/   ← React SPA (Vite)
```

## Franchises

- Nantes Centre
- Rennes Nord
- Brest Ocean
