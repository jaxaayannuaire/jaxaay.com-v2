# Jaxaay Annuaire V2 — Architecture de référence

**Version : 0.3**
**Date : 5 septembre 2026**
**Statut : Architecture fonctionnelle validée — pré-développement**
**Organisation : Jaxaay Group**

---

# 1. Vision

Jaxaay Annuaire V2 est une plateforme SaaS multi-annuaire destinée à structurer,
référencer, rechercher et mettre en relation :

- personnes ;
- entreprises ;
- établissements ;
- organisations ;
- professionnels ;
- annonces ;
- documents et archives ;
- localités ;
- contenus associés.

La priorité V1 n'est pas la marketplace.

La priorité est un moteur d'annuaire :

- performant ;
- léger ;
- hautement configurable ;
- relationnel ;
- extensible ;
- API-first ;
- SEO-first ;
- multi-plateforme ;
- adapté au Sénégal puis à l'Afrique.

---

# 2. Principe architectural

Un nouveau vertical doit pouvoir être créé principalement par configuration.

```text
Directory
    ↓
Listing Type
    ↓
Fields / Fieldsets / Repeaters
    ↓
Taxonomies
    ↓
Relations
    ↓
Locations
    ↓
Search Schema
    ↓
Views
    ↓
Permissions / Entitlements