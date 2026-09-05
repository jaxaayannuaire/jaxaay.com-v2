# ADR-002 — Construire un Directory Engine générique et configurable

- **Statut :** Accepté
- **Date :** 2026-09-05

## Contexte
Jaxaay doit gérer plusieurs sous-annuaires : entreprises, profils, annonces, documents/archives, puis d’autres verticales.

Créer une architecture différente pour chaque vertical entraînerait duplication, dette technique et rigidité.

## Décision
Construire un moteur générique basé sur :

```text
Directory
→ ListingType
→ Fields
→ Fieldsets / Repeaters
→ Taxonomies
→ Relations
→ Locations
→ Search Schema
→ Views
→ Permissions / Entitlements
```

Les nouveaux verticals doivent être créés principalement par configuration.

## Inspirations
- Directories Pro.
- ListifyPro.
- Atlas.
- ListPlace.

## Conséquences
### Positives
- Souplesse.
- Réutilisation.
- Facilité d’industrialisation.
- Préparation à des projets futurs comme AGPS/WaPASTEF.

### Négatives
- Modèle de données plus abstrait.
- Besoin de règles strictes pour éviter un moteur trop générique et difficile à maintenir.
