# /project:migration-audit — Audit des migrations Doctrine

Verifie la coherence entre les entites Doctrine et les migrations existantes.

## Etapes

1. Lis toutes les entites dans `api/src/Entity/`
2. Lis toutes les migrations dans `api/migrations/`
3. Pour chaque entite, verifie que :
   - Chaque champ `#[ORM\Column]` a une migration correspondante
   - Les types de colonnes correspondent (string → VARCHAR, datetime → DATETIME, etc.)
   - Les index et contraintes sont couverts
4. Cherche les migrations destructives dangereuses :
   - `DROP TABLE`
   - `DROP COLUMN`
   - `ALTER TABLE ... MODIFY` qui reduit la taille d'une colonne
5. Verifie l'ordre des migrations (timestamps coherents)

## Format de sortie

```
## Audit migrations — [date]

### Entites analysees
- Appointment (X champs)

### Coherence entites ↔ migrations
- [ ] OK / PROBLEME : description

### Migrations dangereuses
- Aucune trouvee / Liste des risques

### Recommendation
- Faut-il generer une nouvelle migration ? Oui/Non + pourquoi
```

## Regle importante

Les fichiers de migration (`migrations/*.php`) ne doivent JAMAIS etre modifies directement.
Si une correction est necessaire, generer une nouvelle migration avec `php bin/console make:migration`.
