# /project:status — Sante du projet

Donne un apercu rapide de l'etat du projet PneuTracker.

## Etapes

1. Lance `git log --oneline -10` pour les derniers commits
2. Lance `git status` pour voir les fichiers non commites
3. Cherche les `TODO` et `FIXME` dans le code : `grep -r "TODO\|FIXME" api/src/ web/src/`
4. Verifie si les tests passent : `cd api && php bin/phpunit --testdox 2>&1 | tail -20`
5. Compte les entites Doctrine : `ls api/src/Entity/`
6. Compte les composants React : `ls web/src/components/`

## Format de sortie

```
## Etat du projet — [date]

### Derniers commits
(liste des 5 derniers)

### Tests
- X tests passent / Y echouent
- Tests casses connus : testCreateAppointment, testCreateAppointmentInvalidPhone

### TODOs en attente
- liste des TODO trouves

### Stats
- Entites : X
- Controlleurs : X
- Composants React : X
- Fichiers non commites : X
```
