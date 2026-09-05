# ADR-009 — Attribuer des références métier stables

- **Statut :** Accepté
- **Date :** 2026-09-05

## Contexte
Les entités publiques ont besoin d’identifiants lisibles, stables et indépendants des IDs internes PostgreSQL.

## Décision
Utiliser le format :

```text
PREFIX + YYMM + sequence
```

Exemples :

```text
PF2609-0001   → Profil
AN2609-0001   → Annonce
DOC2609-0001  → Document
```

Les préfixes sont configurables par type.

## Règles
- référence unique ;
- génération transactionnelle ;
- jamais recyclée ;
- immuable après attribution ;
- indépendante de `id` et `public_id`.

## Conséquences
### Positives
- Références faciles à communiquer.
- Meilleure traçabilité.
- Import/export simplifié.
- Support client facilité.

### Négatives
- Nécessite un service de séquence fiable.
