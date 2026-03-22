# /project:review — Revue de code qualite

Analyse les changements en cours et produis une revue de code structuree.

## Etapes

1. Lance `git diff` pour voir les modifications staged et unstaged
2. Lance `git diff --cached` pour les modifications prete a etre commitees
3. Pour chaque fichier modifie :
   - Verifie le respect des conventions (PSR-12 pour PHP, pas de semicolons en JS)
   - Cherche les problemes de securite (injection SQL, XSS, donnees non validees)
   - Verifie que les types sont corrects (PHP strict_types, pas de `any` en JS)
   - Cherche les `dd()`, `dump()`, `console.log()` oublies
4. Verifie que les tests existants ne sont pas casses par les changements
5. Suggere des tests manquants si du code metier a ete modifie

## Format de sortie

```
## Revue de code — [date]

### Fichiers modifies
- liste des fichiers

### Problemes trouves
- [ ] probleme 1 (severite: haute/moyenne/basse)
- [ ] probleme 2

### Points positifs
- ce qui est bien fait

### Suggestions
- ameliorations possibles
```
