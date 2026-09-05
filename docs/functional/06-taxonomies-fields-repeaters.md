# Jaxaay Annuaire V2 — Champs dynamiques, taxonomies et repeaters

**Version : 0.1**  
**Statut : Spécification fonctionnelle V1**

## 1. Objectif

Fournir un moteur flexible sans créer une table spécifique pour chaque nouveau besoin métier.

## 2. Types de champs

```text
text
textarea
integer
decimal
boolean
select
multiselect
radio
checkbox
date
datetime
email
phone
url
currency
location
file
image
video
relation
fieldset
repeater
```

## 3. Propriétés

Chaque champ peut être :

- obligatoire ;
- facultatif ;
- searchable ;
- filterable ;
- sortable ;
- public ;
- privé ;
- conditionnel ;
- visible selon rôle ;
- visible selon état ;
- validé par règles.

## 4. Conditions

Exemple :

```text
si type_profile = "Élu"
→ afficher type_mandat
→ afficher circonscription
→ afficher législature
```

## 5. Fieldsets

Un fieldset regroupe plusieurs champs.

Exemple :

```text
Adresse professionnelle
├── établissement
├── adresse
├── téléphone
└── horaires
```

## 6. Repeaters

Un groupe peut être répété.

Exemple :

```text
Expériences[]
├── organisation
├── fonction
├── date_debut
├── date_fin
└── description
```

Usages :

- diplômes ;
- expériences ;
- mandats ;
- certifications ;
- agences ;
- contacts ;
- menus ;
- équipements ;
- langues.

## 7. Taxonomies

Exemples :

- catégories ;
- professions ;
- spécialités ;
- tags ;
- types d'organisation ;
- types de documents ;
- types de contrats ;
- mandats.

## 8. Gouvernance

Les taxonomies structurantes sont contrôlées.

Un utilisateur peut proposer un terme :

```text
suggested
→ pending
→ approved / merged / rejected
```

## 9. Hiérarchie

Une taxonomie peut être hiérarchique :

```text
Santé
└── Médecine
    └── Ophtalmologie
```

## 10. Import / export

Champs, fieldsets et taxonomies doivent être inclus dans les Directory Blueprints et compatibles avec DataExchange.
