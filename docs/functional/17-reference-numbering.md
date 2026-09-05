# Jaxaay Annuaire V2 — Références métier

**Version : 0.1**  
**Statut : Spécification fonctionnelle V1**

## 1. Objectif

Attribuer à chaque fiche publiée une référence humaine stable et indépendante des IDs internes.

## 2. Format

```text
PREFIX + YYMM + sequence
```

Exemples :

```text
PF2609-0001
AN2609-0001
DOC2609-0001
```

## 3. Préfixes

Les préfixes sont définis par type de fiche.

Exemples validés :

- `PF` : Profil ;
- `AN` : Annonce ;
- `DOC` : Document.

D'autres préfixes seront validés avant implémentation.

## 4. Règles

Une référence :

- est unique ;
- est immuable ;
- n'est jamais recyclée ;
- reste associée à la fiche même après archivage ;
- peut être utilisée dans l'import/export ;
- peut être utilisée par le support.

## 5. Génération

La séquence doit être transactionnelle afin d'éviter les collisions.

## 6. Distinction

```text
id
→ identifiant interne base

public_id
→ identifiant technique API

reference
→ identifiant humain métier
```

## 7. Fusion

Lors d'une fusion de doublons, les anciennes références doivent rester traçables dans l'audit ou les alias de référence.
