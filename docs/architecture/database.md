# Jaxaay Annuaire V2 — Architecture de la base de données

**Version : 0.1**
**Statut : Conception — aucune migration à créer avant validation**
**SGBD cible : PostgreSQL**

---

## 1. Principes

Le modèle doit rester :

- performant ;
- configurable ;
- relationnel ;
- multi-annuaire ;
- compatible API ;
- compatible recherche externe ;
- extensible sans multiplication des tables par secteur.

Principe fondamental :

User ≠ PersonProfile ≠ Organization ≠ DirectoryEntry

Une fiche publique existe indépendamment du compte qui la gère.

---

# 2. Vue globale

```text
SAAS CORE
│
├── users
├── organizations
├── organization_user
├── plans
├── subscriptions
├── modules
└── entitlements
        │
        ▼
DIRECTORY ENGINE
│
├── directories
├── listing_types
├── directory_entries
│
├── field_definitions
├── field_groups
├── field_values
│
├── taxonomies
├── taxonomy_terms
│
├── locations
│
└── entry_relationships
        │
        ├── person_profiles
        ├── organization_profiles
        ├── announcements
        └── documents