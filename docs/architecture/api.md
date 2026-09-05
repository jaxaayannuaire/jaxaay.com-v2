# Jaxaay Annuaire V2 — Architecture API

**Version : 0.1**  
**Statut : Conception**  
**API cible : REST JSON / Laravel 13**

## 1. Principes

L’API constitue la couche commune pour :

```text
Web
Flutter
Telegram Bot
Agents IA
Imports
Connecteurs externes
```

Principes :

- API-first ;
- versionnée ;
- tenant-aware ;
- sécurisée ;
- pagination systématique ;
- opérations idempotentes lorsque nécessaire ;
- aucune logique métier importante exclusivement côté frontend.

Base :

```text
/api/v1/
```

---

## 2. Authentification

Laravel Sanctum.

Endpoints :

```text
POST /api/v1/auth/login
POST /api/v1/auth/logout
POST /api/v1/auth/register
GET  /api/v1/auth/me
POST /api/v1/auth/forgot-password
POST /api/v1/auth/reset-password
```

Les tokens doivent pouvoir être associés à des capacités et appareils lorsque nécessaire.

---

## 3. Contexte Organization

Une requête métier privée doit résoudre explicitement son organisation.

Approche inspirée de Yessal ERP :

```http
X-Organization-Id: <public_id>
```

Le serveur vérifie obligatoirement :

```text
User
  ↓ appartient à
Organization
  ↓ possède droits
Resource
```

Le header seul ne constitue jamais une autorisation.

---

# 4. Réponse standard

Succès :

```json
{
  "data": {},
  "meta": {}
}
```

Collection :

```json
{
  "data": [],
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 125
  },
  "links": {
    "next": "...",
    "previous": null
  }
}
```

Erreur :

```json
{
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Les données fournies sont invalides.",
    "details": {}
  }
}
```

---

# 5. Identifiants publics

L’API ne doit pas dépendre des clés séquentielles PostgreSQL.

Exposition :

```text
public_id
reference
slug
```

Exemple :

```json
{
  "id": "01K...",
  "reference": "PF2609-0043",
  "slug": "docteur-xxx"
}
```

---

# 6. Directories

```text
GET    /directories
GET    /directories/{directory}
POST   /directories
PATCH  /directories/{directory}
```

Administration uniquement pour les opérations d’écriture structurantes.

---

# 7. Listing Types

```text
GET    /listing-types
GET    /listing-types/{type}
POST   /listing-types
PATCH  /listing-types/{type}
```

Une réponse peut exposer son schéma :

```json
{
  "code": "doctor",
  "fields": [],
  "taxonomies": [],
  "filters": [],
  "permissions": {}
}
```

Flutter et Web peuvent ainsi générer certains formulaires dynamiquement.

---

# 8. Directory Entries

Ressource publique centrale :

```text
GET    /entries
GET    /entries/{entry}
POST   /entries
PATCH  /entries/{entry}
DELETE /entries/{entry}
```

Filtres généraux :

```text
directory
type
status
location
category
verified
featured
```

---

# 9. Profils

API spécialisée :

```text
GET   /profiles
GET   /profiles/{profile}
POST  /profiles
PATCH /profiles/{profile}
```

Exemple :

```json
{
  "reference": "PF2609-0001",
  "display_name": "...",
  "professions": [],
  "specialties": [],
  "locations": [],
  "affiliations": [],
  "verification": {}
}
```

Les données privées ne sont jamais incluses sans autorisation.

---

# 10. Organisations / entreprises publiques

```text
GET   /businesses
GET   /businesses/{business}
POST  /businesses
PATCH /businesses/{business}
```

Une entreprise publique peut exister sans `Organization` SaaS propriétaire.

---

# 11. Affiliations

```text
GET    /profiles/{profile}/affiliations
POST   /profiles/{profile}/affiliations
PATCH  /affiliations/{affiliation}
DELETE /affiliations/{affiliation}
```

Exemple :

```json
{
  "organization": "...",
  "role": "Ophtalmologue",
  "start_date": "2024-01-01",
  "is_current": true,
  "verified": true
}
```

---

# 12. Annonces

```text
GET    /announcements
GET    /announcements/{announcement}
POST   /announcements
PATCH  /announcements/{announcement}
DELETE /announcements/{announcement}
```

Un client publiant régulièrement utilise principalement ce domaine.

Création :

```text
draft
→ moderation
→ published
```

Le système peut notifier l’administration lorsqu’une annonce semble relever d’un autre type ou annuaire.

---

# 13. Documents / Archives

```text
GET    /documents
GET    /documents/{document}
POST   /documents
PATCH  /documents/{document}
```

Relations :

```text
document → profiles
document → organizations
document → locations
```

La carte géographique reste optionnelle et généralement désactivée pour ce vertical.

---

# 14. Taxonomies

Lecture publique :

```text
GET /taxonomies
GET /taxonomies/{taxonomy}/terms
```

Administration :

```text
POST   /admin/taxonomies/{taxonomy}/terms
PATCH  /admin/terms/{term}
```

Les clients ne créent pas directement les termes contrôlés.

Suggestion :

```text
POST /taxonomy-suggestions
```

Workflow :

```text
proposed
→ reviewed
→ approved / merged / rejected
```

---

# 15. Location Core

```text
GET /locations
GET /locations/{location}
GET /locations/{location}/children
```

Recherche :

```text
GET /locations/search?q=kaolack
```

Administration :

```text
POST  /admin/locations
PATCH /admin/locations/{location}
POST  /admin/locations/merge
```

Un seul référentiel géographique est utilisé par toute la plateforme.

---

# 16. Recherche globale

Endpoint principal :

```text
GET /search
```

Exemple :

```text
/search?q=ophtalmologue+kaolack
```

Réponse :

```json
{
  "data": {
    "profiles": [],
    "businesses": [],
    "announcements": [],
    "documents": [],
    "locations": []
  },
  "meta": {
    "query": "ophtalmologue kaolack"
  }
}
```

Ranking configurable :

```text
relevance
entity_type
location
verification
quality
popularity
featured
freshness
```

---

# 17. Recherche facettée

Exemple :

```text
GET /search?
q=médecin
&location=kaolack
&specialty=ophtalmologie
&verified=true
```

Réponse :

```json
{
  "data": [],
  "facets": {
    "specialties": {},
    "locations": {},
    "verified": {}
  }
}
```

Web/Livewire met à jour :

```text
résultats
facettes
compteurs
carte
URL
```

sans rechargement complet.

---

# 18. Autocomplete

```text
GET /search/suggest?q=oph
```

Peut retourner :

```text
Profils
Professions
Entreprises
Catégories
Localités
```

---

# 19. Géorecherche

```text
GET /search?
lat=14.18
&lng=-16.25
&radius=25
```

Support :

- distance ;
- zone de service ;
- tri proximité ;
- carte.

---

# 20. Reviews

```text
GET  /entries/{entry}/reviews
POST /entries/{entry}/reviews
```

Pour avis multi-critères :

```json
{
  "scores": {
    "competence": 5,
    "reactivite": 4
  },
  "comment": "..."
}
```

Les critères proviennent de la configuration du type.

---

# 21. Signalements

```text
POST /entries/{entry}/reports
POST /reviews/{review}/reports
```

Administration :

```text
GET   /admin/moderation/reports
PATCH /admin/moderation/reports/{report}
```

Le système peut automatiquement passer une fiche en :

```text
pending_review
```

mais la décision finale reste humaine.

---

# 22. Claim

```text
POST /entries/{entry}/claims
GET  /claims/{claim}
POST /claims/{claim}/evidence
```

Administration :

```text
POST /admin/claims/{claim}/approve
POST /admin/claims/{claim}/reject
```

Claim et verification restent deux processus différents.

---

# 23. Verification / KYC

```text
GET  /entries/{entry}/verifications
POST /entries/{entry}/verification-requests
```

Administration :

```text
POST /admin/verifications/{verification}/approve
POST /admin/verifications/{verification}/reject
```

Sources possibles :

```text
identity
professional_order
RCCM
NINEA
official_source
external_platform
```

---

# 24. Contacts privés

L’API publique ne retourne que les contacts dont la visibilité le permet.

Exemple :

```json
{
  "contacts": [
    {
      "type": "website",
      "value": "...",
      "visibility": "public"
    }
  ]
}
```

Un téléphone privé n’est pas masqué simplement visuellement : il est **absent de la réponse API**.

---

# 25. Leads

```text
POST /entries/{entry}/contact
POST /quote-requests
```

Exemple :

```json
{
  "subject": "Consultation",
  "message": "...",
  "contact_preference": "jaxaay"
}
```

Le destinataire reçoit le lead sans nécessité d’exposer ses coordonnées.

---

# 26. Favoris

```text
GET    /me/favorites
POST   /me/favorites/{entry}
DELETE /me/favorites/{entry}
```

---

# 27. Recherches sauvegardées

```text
GET    /me/saved-searches
POST   /me/saved-searches
DELETE /me/saved-searches/{search}
```

Permet :

```text
nouveau job à Dakar
nouvelle annonce immobilière Kaolack
nouveau document administratif
```

avec alertes futures.

---

# 28. DataExchange

Import :

```text
POST /imports
GET  /imports/{import}
```

Options :

```json
{
  "type": "csv",
  "directory": "profiles",
  "dry_run": true,
  "mapping": {}
}
```

Médias :

```text
CSV
+
ZIP
```

L’import est traité par queue.

---

# 29. Import — cycle

```text
uploaded
→ validating
→ dry_run
→ ready
→ processing
→ completed / failed
```

Endpoints :

```text
POST /imports/{import}/validate
POST /imports/{import}/execute
POST /imports/{import}/cancel
GET  /imports/{import}/errors
```

---

# 30. Directory Blueprints

Export :

```text
GET /admin/directories/{directory}/blueprint
```

Import :

```text
POST /admin/directory-blueprints
```

Contenu :

```text
listing types
fields
fieldsets
taxonomies
views
filters
SEO
permissions
```

Permettra notamment une future configuration AGPS/WaPASTEF sur le moteur Jaxaay.

---

# 31. Détection des doublons

```text
POST /duplicates/check
```

Exemple :

```json
{
  "entity_type": "business",
  "name": "Clinique ABC",
  "phone": "...",
  "location": "Kaolack"
}
```

Réponse :

```json
{
  "matches": [
    {
      "entry": "...",
      "score": 0.92,
      "signals": [
        "normalized_name",
        "phone",
        "location"
      ]
    }
  ]
}
```

---

# 32. Fusion

Administration uniquement :

```text
POST /admin/entries/merge
```

La fusion :

- est auditée ;
- conserve les références historiques ;
- ne doit pas être exécutée automatiquement par un agent IA.

---

# 33. IA — suggestions

API interne future :

```text
POST /internal/ai/classify
POST /internal/ai/duplicate-suggestions
POST /internal/ai/location-suggestions
```

Résultat :

```text
suggestion
confidence
reason
```

et non une modification immédiate.

---

# 34. External Profiles

```text
GET    /entries/{entry}/external-profiles
POST   /entries/{entry}/external-profiles
DELETE /external-profiles/{profile}
```

Providers :

```text
google_business
linkedin
facebook
youtube
tiktok
```

---

# 35. Synchronisation externe

```text
POST /external-profiles/{profile}/sync
GET  /external-profiles/{profile}/sync-status
```

Direction :

```text
pull
push
bidirectional
```

uniquement lorsque le fournisseur l’autorise.

---

# Core SaaS 1A implémenté

Les routes `/api/v1/auth/*`, `/api/v1/organizations` et
`/api/v1/organization/context` sont disponibles avec Sanctum. Le contexte est
résolu par `X-Organization-Id` (ULID public) et contrôlé par membership.

# Core SaaS 1B — Plans et souscriptions

Le catalogue public expose uniquement les plans actifs :

```text
GET /api/v1/plans
GET /api/v1/plans/{slug}
```

Les souscriptions utilisent le contexte tenant résolu par `X-Organization-Id` :

```text
GET  /api/v1/organization/subscription
POST /api/v1/organization/subscription
```

Le payload de création contient le `plan` identifié par son slug et un cycle
`monthly` ou `yearly`. La création est réservée aux rôles `owner` et `admin`,
commence au statut `pending` et snapshot le prix et la devise du plan. Un
membre peut consulter la souscription courante. Une seule souscription non
terminale (`pending`, `trialing`, `active`, `grace`) est autorisée par
organisation ; `cancelled` et `expired` sont historiques.

# 36. OAuth

```text
GET /integrations/{provider}/authorize
GET /integrations/{provider}/callback
```

Les tokens ne sont jamais retournés aux clients.

---

# 37. Telegram Bot

Le bot utilise uniquement l’API.

Exemples futurs :

```text
POST /bot/announcements/drafts
GET  /bot/notifications
GET  /bot/me
```

Mais idéalement les mêmes endpoints métier standards sont réutilisés avec des scopes spécifiques.

---

# 38. Publication depuis Telegram

Flux :

```text
Telegram
 ↓
Bot
 ↓
API
 ↓
Announcement draft
 ↓
Validation règles
 ↓
Moderation
 ↓
Published
```

Jamais :

```text
Telegram → PostgreSQL
```

---

# 39. Notifications

Préférences :

```text
GET   /me/notification-preferences
PATCH /me/notification-preferences
```

Canaux :

```text
email
push
telegram
```

Les événements métier restent indépendants du canal.

---

# 40. Webhooks

Jaxaay pourra recevoir :

```text
POST /webhooks/google-business
POST /webhooks/payments/{provider}
```

Les webhooks doivent prévoir :

- signature ;
- idempotence ;
- replay protection ;
- journalisation.

---

# 41. Idempotence

Pour opérations sensibles :

```http
Idempotency-Key: <uuid>
```

Applicable notamment :

- imports ;
- paiements ;
- création via bot ;
- synchronisation externe ;
- webhooks.

---

# 42. Pagination

Collections publiques :

```text
page
per_page
```

Pour gros volumes ou synchronisation :

```text
cursor
```

Une limite maximale côté serveur est obligatoire.

---

# 43. Tri

Convention :

```text
?sort=name
?sort=-published_at
```

Seuls les champs explicitement autorisés sont triables.

---

# 44. Sparse fields

Option future :

```text
?fields[profiles]=reference,name,profession
```

utile pour Flutter et listes volumineuses.

---

# 45. Includes

Relations explicitement autorisées :

```text
?include=location,affiliations
```

Évite :

- N+1 ;
- réponses gigantesques ;
- exposition accidentelle de relations privées.

---

# 46. Cache HTTP

Pour données publiques stables :

```text
ETag
Last-Modified
Cache-Control
```

Particulièrement :

```text
taxonomies
locations
directory schemas
public entries
```

---

# 47. Rate Limiting

Profils distincts :

```text
anonymous
authenticated
business
bot
integration
admin
```

Endpoints sensibles :

```text
login
search
contact
reports
claims
imports
external sync
```

possèdent des limites spécifiques.

---

# 48. Permissions

Approche :

```text
Role
+
Permission
+
Entitlement
+
Resource Policy
```

Exemple :

```text
Utilisateur autorisé
+
Plan autorise annonces
+
Quota non atteint
+
Organization possède la ressource
```

→ création autorisée.

---

# 49. Entitlements API

```text
GET /organizations/{organization}/entitlements
```

Exemple :

```json
{
  "modules": {
    "announcements": true,
    "advanced_analytics": false
  },
  "limits": {
    "listings": 10,
    "featured_listings": 2
  },
  "usage": {
    "listings": 4
  }
}
```

---

# 50. SEO et API

Les API retournent les éléments nécessaires :

```text
slug
canonical
meta
schema_type
breadcrumbs
```

mais la génération HTML SEO reste principalement côté Web.

---

# 51. Administration

Namespace :

```text
/api/v1/admin/
```

Principaux domaines :

```text
users
organizations
directories
entries
profiles
taxonomies
locations
imports
duplicates
claims
verifications
moderation
external-profiles
audit
```

Chaque opération sensible doit générer un audit log.

---

# 52. Statistiques

Exemples :

```text
GET /organizations/{organization}/analytics
GET /entries/{entry}/analytics
```

Données :

```text
views
search impressions
profile opens
contact requests
favorites
external clicks
```

La collecte analytique doit respecter la politique de confidentialité.

---

# 53. Health API

Infrastructure :

```text
GET /health
GET /health/ready
```

Sans exposition de secrets internes.

---

# 54. Versioning

Première version :

```text
/api/v1
```

Une rupture incompatible entraîne :

```text
/api/v2
```

Les changements compatibles restent dans `v1`.

---

# 55. Dépréciation

Avant suppression d’un endpoint :

```text
deprecated
→ documentation
→ délai
→ nouvelle route
→ suppression version majeure
```

---

# 56. OpenAPI

La documentation API devra être générable en :

```text
OpenAPI 3.x
```

Objectifs :

- documentation ;
- tests ;
- génération clients ;
- intégration Flutter ;
- intégration bot ;
- QA.

---

# 57. Tests API

Chaque domaine critique doit couvrir :

```text
success
validation
authentication
authorization
tenant isolation
visibility
entitlements
quotas
pagination
duplicates
idempotence
```

---

# 58. Règle fondamentale

Un client externe doit pouvoir utiliser Jaxaay uniquement via l’API sans connaître :

- structure PostgreSQL ;
- implémentation Laravel ;
- logique interne des connecteurs.

Architecture :

```text
Client
 ↓
API Contract
 ↓
Application Services
 ↓
Domain
 ↓
Infrastructure
```

L’API constitue donc un **contrat stable**, pas une simple exposition des modèles Eloquent.
