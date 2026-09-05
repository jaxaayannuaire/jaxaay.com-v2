# Jaxaay Annuaire V2 — Architecture de la base de données

**Version : 0.1**
**Statut : Core SaaS 1B validé — migrations plans et subscriptions présentes**
**SGBD cible : PostgreSQL**

Core SaaS 1B ajoute `plans` et `subscriptions` au socle 1A. Les plans sont
globaux ; les souscriptions appartiennent aux organisations et snapshotent le
prix, la devise et le cycle au moment de leur création. Aucun modèle Annuaire,
paiement ou marketplace n'est introduit.

## Core SaaS 1B — plans et subscriptions

`plans` est un catalogue global : `slug` est unique, `price_monthly` est
obligatoire, `price_yearly` est nullable lorsque le cycle annuel n'est pas
proposé, et `currency` est une devise majuscule de trois caractères. Aucun
champ de quota, entitlement ou module n'est stocké dans cette table.

`subscriptions` appartient à une organisation et référence un plan. Elle
conserve `public_id` (ULID), `billing_cycle`, `price` et `currency` comme
snapshot de souscription, ainsi que les dates de cycle et de statut. Une
contrainte PostgreSQL garantit au plus une subscription de statut
`pending`, `trialing`, `active` ou `grace` par organisation ; les statuts
`cancelled` et `expired` restent historiques.

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
