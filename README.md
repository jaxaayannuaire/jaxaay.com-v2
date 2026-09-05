# Jaxaay Annuaire V2

> Statut : bootstrap technique validé ; développement métier non commencé.

Le Core SaaS 1A (authentification Sanctum, organisations, memberships et
contexte tenant) est maintenant implémenté dans `apps/api`.

## Bootstrap validé

Le backend Laravel 13 est initialisé dans `apps/api` et le scaffold Flutter
Android/Web dans `apps/mobile`. Les contrôles de base Laravel et Flutter
passent. PostgreSQL reste la base cible ; aucun domaine métier ni composant de
marketplace n'est encore implémenté.

Plateforme SaaS d’annuaire professionnel, de profils, d’annonces et de contenus structurés développée par **Jaxaay Group**.

> Statut : architecture et spécifications V1 en cours de consolidation.

## Vision

Jaxaay Annuaire V2 vise à fournir un moteur d’annuaire :

- performant ;
- léger ;
- hautement configurable ;
- multi-annuaire ;
- API-first ;
- multi-plateforme ;
- adapté au Sénégal et à l’Afrique.

Un nouveau vertical doit pouvoir être créé principalement par configuration :

Listing Type + Fields + Taxonomies + Filters + Views + Permissions + Entitlements

## Stack cible

- PHP 8.3+
- Laravel 13
- PostgreSQL
- Laravel Sanctum
- Redis / Queues / Scheduler
- Livewire 4
- Tailwind CSS 4
- Laravel Scout
- Typesense ou Meilisearch à valider
- Flutter : Android, iOS, Web et Desktop

## Architecture SaaS

User
→ Organization
→ Subscription
→ Plan
→ Modules
→ Entitlements
→ Quotas
→ Domaines métier

Le Core SaaS reprend les principes architecturaux validés dans **Yessal ERP SaaS**.

## Domaines V1

### Directory Engine
- types et sous-types d’annuaires ;
- champs dynamiques ;
- fieldsets / repeaters ;
- relations inter-entités ;
- taxonomies contrôlées ;
- vues configurables.

### Profils
Personnes physiques vérifiées ou documentées :

- professionnels ;
- médecins et professions réglementées ;
- scientifiques ;
- universitaires ;
- religieux ;
- personnalités publiques ;
- responsables politiques ;
- autres profils configurables.

`User` et `PersonProfile` sont deux entités différentes.

### Entreprises et organisations
- établissements ;
- entreprises ;
- associations ;
- institutions ;
- services ;
- structures professionnelles.

Une personne peut être reliée à plusieurs organisations.

### Annonces
Vertical destiné aux publications fréquentes :

- emplois ;
- immobilier ;
- automobile ;
- services ;
- opportunités ;
- autres catégories configurables.

### Documents / Archives
Annuaire simplifié destiné notamment aux :

- décrets ;
- rapports ;
- documents administratifs ;
- projets ;
- publications institutionnelles ;
- archives numériques.

## Recherche

Recherche globale multi-entités :

PersonProfiles
→ Établissements
→ Entreprises
→ Organisations
→ Annonces
→ Documents
→ Localités

Exemple :

`ophtalmologue kaolack`

La recherche doit privilégier les profils pertinents avant les établissements correspondants.

Fonctions prévues :

- full-text ;
- facettes ;
- filtres dynamiques ;
- géorecherche ;
- AJAX / Livewire sans rechargement complet ;
- carte synchronisée ;
- URLs partageables ;
- ranking configurable.

## Localisation

Un **Location Core unique** est partagé par tous les annuaires afin d’éviter les doublons.

Pays
→ Région
→ Département
→ Commune
→ Quartier
→ Adresse
→ Coordonnées GPS

Les utilisateurs ne créent pas librement les localités, catégories ou taxonomies principales.

## Identifiants de référence

Chaque entité publiée reçoit une référence lisible.

Exemples :

- `PF2609-0001` : profil ;
- `AN2609-0001` : annonce ;
- `DOC2609-0001` : document.

Format :

`PREFIX + AAMM + compteur`

## Confidentialité

Les coordonnées personnelles sont privées par défaut.

Chaque contact possède une politique de visibilité :

- private ;
- public ;
- authenticated ;
- organization ;
- owner_only.

Un utilisateur peut recevoir une demande de contact sans publier son téléphone ou son email.

## Validation et modération

Workflow général :

draft
→ pending_review
→ published
→ verified
→ archived / suspended

Les profils sensibles ou institutionnels ne sont jamais validés automatiquement.

La modération comprend :

- signalements ;
- catégories de signalement ;
- score de gravité ;
- seuils ;
- audit ;
- validation humaine.

## Data Exchange

Module V1 prioritaire :

- import CSV ;
- export CSV ;
- mapping dynamique ;
- catégories ;
- tags ;
- localités ;
- champs personnalisés ;
- images ZIP ;
- Directory Blueprints JSON ;
- traitement par lots ;
- dry-run ;
- journal d’import ;
- rollback ;
- détection de doublons.

Il servira notamment à migrer les données de **Jaxaay Annuaire V1**.

## External Profiles

Connecteurs prévus :

- Google Business Profile ;
- LinkedIn ;
- Facebook ;
- YouTube ;
- TikTok.

Objectifs :

- recherche de correspondances ;
- détection des doublons ;
- synchronisation ;
- rapprochement avec les fiches Jaxaay ;
- journalisation des opérations.

Les capacités exactes dépendent des permissions offertes par chaque plateforme.

## Notifications

Architecture événementielle :

Domain Event
→ Notification Service
→ Email / Push / Telegram / autres canaux

Un bot Telegram Jaxaay est prévu comme client de l’API, sans accès direct à PostgreSQL.

## Marketplace

La marketplace transactionnelle est volontairement **hors V1**.

L’architecture préparera néanmoins l’ajout futur de :

- produits ;
- panier ;
- commandes ;
- commissions ;
- paiements ;
- vendeurs ;
- stocks ;
- livraison.

## Références fonctionnelles

Les principales références étudiées sont :

- Directories Pro ;
- Expat-Dakar ;
- Go Africa Online ;
- Google Business Profile ;
- Kompass ;
- ListifyPro ;
- Atlas ;
- ListPlace ;
- Bulistio ;
- Jumia Sénégal.

Aucun de ces produits n’est utilisé comme architecture complète : Jaxaay reprend et améliore uniquement les concepts pertinents.

## Structure cible

jaxaay-annuaire/
├── apps/
│   ├── api/
│   ├── web/
│   └── mobile/
├── docs/
│   ├── architecture/
│   ├── functional/
│   ├── security/
│   ├── development/
│   ├── deployment/
│   └── decisions/
├── infrastructure/
├── README.md
├── ROADMAP.md
├── CHANGELOG.md
└── AGENTS.md

## Documentation

Documents principaux :

- `docs/architecture/architecture.md`
- `docs/architecture/database.md`
- `docs/architecture/api.md`
- `docs/architecture/external-profiles.md`
- `docs/functional/`
- `ROADMAP.md`
- `CHANGELOG.md`
- `AGENTS.md`

## Gouvernance

- **GitHub** : source de vérité.
- **ChatGPT** : architecture et pilotage technique.
- **Codex** : implémentation, tests et refactoring.
- **ChatGPT Work** : documentation, analyse et QA.

Une décision discutée n’est considérée comme implémentée qu’après présence dans le dépôt et validation des tests.

## Licence

À définir avant publication du dépôt.
