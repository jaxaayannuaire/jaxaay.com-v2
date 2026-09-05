# ADR-004 — Utiliser un Location Core unique

- **Statut :** Accepté
- **Date :** 2026-09-05

## Contexte
Les sous-annuaires ont tous besoin de géolocalisation. Des référentiels séparés provoqueraient doublons, fautes et incohérences.

## Décision
Créer un référentiel géographique central partagé par tous les annuaires :

```text
Country
→ Region
→ Department
→ Commune
→ District / Neighborhood
```

Les utilisateurs ne peuvent pas créer librement les localités structurantes. Ils peuvent proposer des corrections ou nouvelles valeurs, soumises à validation.

## Évolutions prévues
- Détection automatique de doublons.
- Scripts de normalisation.
- Assistance IA pour correction et fusion.
- Géocodage et coordonnées GPS.

## Conséquences
### Positives
- Cohérence globale.
- Meilleure recherche géographique.
- Réduction forte des doublons.
- Réutilisable dans tous les verticals.

### Négatives
- Nécessite une gouvernance du référentiel.
- Imports externes plus exigeants.
