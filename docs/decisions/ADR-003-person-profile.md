# ADR-003 — Séparer PersonProfile de User

- **Statut :** Accepté
- **Date :** 2026-09-05

## Contexte
Jaxaay doit référencer des personnes physiques : professionnels, médecins, scientifiques, universitaires, religieux, personnalités publiques, responsables politiques, etc.

Une personne peut être référencée sans posséder de compte Jaxaay.

## Décision
Créer une entité `PersonProfile` distincte de `User`.

```text
User != PersonProfile
```

Les profils pourront être reliés aux organisations via des affiliations professionnelles.

La publication et la validation des profils sensibles ne seront jamais automatiques.

Les coordonnées personnelles seront privées par défaut.

## Conséquences
### Positives
- Modèle fidèle à la réalité.
- Possibilité de référencer une personnalité sans compte utilisateur.
- Meilleure gestion de la confidentialité.
- Relations riches avec entreprises, institutions et documents.

### Négatives
- Complexité supplémentaire pour claim, ownership et vérification.
