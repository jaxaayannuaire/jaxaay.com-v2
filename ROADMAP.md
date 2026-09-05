# Jaxaay Annuaire V2 — ROADMAP

**Statut : conception / pré-développement**  
**Document évolutif**

## Phase 0 — Architecture & gouvernance

- [x] Vision générale
- [x] Core SaaS inspiré de Yessal ERP
- [x] Benchmark Directories Pro / ListifyPro / Atlas / ListPlace / Bulistio
- [x] Benchmark Expat-Dakar / Go Africa Online / Jumia
- [x] Profils personnes physiques
- [x] Documents / Archives
- [x] Location Core unique
- [x] DataExchange CSV/ZIP/JSON
- [x] External Profiles
- [ ] `architecture.md` v0.3 consolidé
- [ ] `database.md`
- [ ] `api.md`
- [ ] ADR
- [ ] AGENTS.md
- [ ] dépôt GitHub initial

## Phase 1 — Core SaaS

- Users
- Organizations
- rôles / permissions
- plans
- subscriptions
- modules
- entitlements
- quotas
- paiements
- audit logs
- notifications

## Phase 2 — Directory Engine

Priorité majeure V1.

- Directory Types
- Listing Types
- champs dynamiques
- champs conditionnels
- fieldsets / repeaters
- relations inter-entités
- taxonomies
- catégories / tags
- Directory Blueprints
- Views configurables
- références `PF2609-0001`, `AN...`, `DOC...`

## Phase 3 — Location Core

Un référentiel partagé par toute la plateforme :

`Pays → Région → Département → Commune → Quartier`

- coordonnées GPS
- géocodage
- zones de service
- anti-doublons
- administration centralisée
- import massif
- corrections assistées ultérieurement par IA

## Phase 4 — Sous-annuaires V1

### Entreprises / Organisations
- établissements
- entreprises
- associations
- institutions

### Profils
- professionnels
- scientifiques
- religieux
- universitaires
- personnalités publiques
- professions réglementées
- affiliations Profil ↔ Organisation
- confidentialité contacts
- validation humaine

### Annonces
- emploi
- immobilier
- automobile
- services
- opportunités

### Documents / Archives
- documents administratifs
- décrets
- rapports
- projets
- archives
- relations vers personnes et organisations

## Phase 5 — Search Engine

- Laravel Scout
- benchmark Typesense / Meilisearch
- recherche multi-entités
- full-text
- facettes
- recherche géographique
- autocomplete
- filtres AJAX/Livewire
- ranking configurable
- profils prioritaires lorsque pertinent
- synchronisation liste/carte

## Phase 6 — Qualité & confiance

- Claims
- KYC
- badges vérifiés
- reviews
- avis multi-critères
- signalements
- modération
- workflows éditoriaux
- anti-spam
- détection de doublons
- fusion contrôlée

## Phase 7 — DataExchange

- migration Jaxaay V1 → V2
- CSV import/export
- ZIP médias
- mapping des champs
- import taxonomies/localités
- dry-run
- validation
- queues
- rapports d'erreurs
- rollback
- import/update par référence
- Blueprints JSON

## Phase 8 — SEO & contenu

- URLs propres
- Schema.org
- OpenGraph
- sitemap
- canonical
- breadcrumbs
- landing pages
- bloc Voir aussi
- relations automatiques
- statistiques vues

## Phase 9 — Leads & engagement

- favoris
- recherches sauvegardées
- alertes
- demandes de contact
- demandes de devis
- notifications
- email
- push Flutter

## Phase 10 — External Profiles

Connecteurs indépendants :

- Google Business Profile
- LinkedIn
- Facebook
- YouTube
- TikTok

Fonctions selon capacités API :

- rapprochement
- recherche anti-doublon
- synchronisation
- import
- édition
- logs
- gestion OAuth

## Phase 11 — Applications

### Web
Laravel 13 + Livewire 4 + Tailwind 4

### Flutter
- Android
- iOS
- Web
- Windows
- macOS

## Phase 12 — Telegram

API destinée au futur Jaxaay Bot :

- alertes
- rappels
- notifications
- consultation
- création de brouillons d'annonces
- suivi validation

Le bot reste un client externe de l'API.

## Phase 13 — Production

- tests automatisés
- tests multi-tenant
- CI/CD
- sauvegardes
- monitoring
- Redis
- queues
- optimisation PostgreSQL
- CDN / médias
- sécurité production

# Hors V1 — Marketplace

Prévue pour V2/V3 :

- catalogue produits
- vendeurs
- commandes
- panier
- stocks
- commissions
- wallet
- payouts
- promotions
- livraison

# Principe de roadmap

Une fonctionnalité passe successivement par :

`Étude → Décision → Documentation → Implémentation → Tests → Validation → Changelog`