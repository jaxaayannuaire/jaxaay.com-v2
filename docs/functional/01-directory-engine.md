# Jaxaay Annuaire V2 — Directory Engine

**Version : 0.1**  
**Statut : Spécification fonctionnelle V1**

## 1. Objectif

Le Directory Engine doit permettre de créer de nouveaux annuaires sans développer une architecture distincte pour chaque secteur.

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

## 2. Directory

Un `Directory` représente un grand domaine fonctionnel.

Exemples :

- Profils ;
- Entreprises ;
- Annonces ;
- Documents / Archives.

Chaque annuaire possède :

- nom ;
- code ;
- slug ;
- statut ;
- types de fiches ;
- configuration de recherche ;
- configuration de vues ;
- règles de publication ;
- permissions ;
- SEO.

## 3. Listing Type

Un `ListingType` décrit la structure d'un sous-type.

Exemples :

```text
Profils
├── Médecin
├── Scientifique
├── Religieux
└── Personnalité publique

Annonces
├── Emploi
├── Immobilier
├── Automobile
└── Services
```

Chaque type peut définir :

- champs ;
- groupes de champs ;
- taxonomies ;
- filtres ;
- règles de validation ;
- règles de visibilité ;
- référence métier ;
- workflow ;
- champs comparables.

## 4. Fiche générique

Toutes les fiches partagent une couche commune :

```text
DirectoryEntry
├── reference
├── title
├── slug
├── status
├── visibility
├── owner
├── organization
├── claimed
├── verified
├── featured
├── published_at
└── quality_score
```

## 5. Relations

Le moteur doit supporter des relations génériques entre fiches.

Exemples :

- personne travaille dans une organisation ;
- document publié par une institution ;
- document associé à une personnalité ;
- entreprise appartient à un groupe ;
- organisation gérée par une personne.

Les relations doivent pouvoir porter :

- type ;
- rôle ;
- dates ;
- statut courant ;
- priorité ;
- vérification ;
- source.

## 6. Vues

Une même donnée peut être affichée sous différentes vues :

- liste ;
- grille ;
- cartes ;
- carte ;
- liste + carte ;
- carrousel ;
- comparaison.

## 7. Directory Blueprints

Un annuaire doit être exportable/importable sous forme de configuration JSON.

Le Blueprint peut contenir :

- listing types ;
- champs ;
- fieldsets ;
- taxonomies ;
- filtres ;
- vues ;
- SEO ;
- permissions.

Objectif : réutiliser le moteur Jaxaay pour d'autres projets, notamment une future migration Laravel d'AGPS/WaPASTEF.

## 8. Règle fonctionnelle principale

Un nouveau vertical doit être créé par configuration chaque fois que possible.

Une nouvelle table ou logique métier spécifique ne doit être introduite que lorsqu'un besoin fonctionnel ne peut pas être correctement représenté par le moteur générique.
