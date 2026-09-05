# Jaxaay Annuaire V2 — External Profiles

**Version : 0.1**  
**Statut : Architecture fonctionnelle / intégrations externes**

## 1. Objectif

Le domaine `ExternalProfiles` relie une fiche Jaxaay à des profils ou pages gérés sur des plateformes tierces, sans rendre le modèle interne dépendant de leurs API.

Providers initiaux :

- Google Business Profile
- LinkedIn
- Facebook
- YouTube
- TikTok

## 2. Principe d’architecture

```text
DirectoryEntry
    ↓
ExternalProfile
    ↓
ExternalProfileProvider
    ├── GoogleBusinessConnector
    ├── LinkedInConnector
    ├── FacebookConnector
    ├── YouTubeConnector
    └── TikTokConnector
```

Chaque fournisseur possède son propre connecteur, ses scopes OAuth, ses règles de synchronisation et ses limites.

## 3. Données principales

`external_profiles` :

- provider
- external_id
- external_url
- entry_id
- sync_status
- sync_mode
- last_sync_at
- last_error
- metadata

`oauth_connections` :

- organization_id / user_id
- provider
- external_account_id
- encrypted_credentials
- scopes
- expires_at
- status

Les secrets et tokens sont chiffrés et ne sont jamais exposés aux clients.

## 4. Modes de synchronisation

```text
pull
push
bidirectional
manual
```

Un mode n’est activé que si l’API du fournisseur l’autorise.

## 5. Google Business Profile

Objectifs prioritaires :

- rechercher une entreprise avant création ;
- détecter les correspondances et doublons ;
- connecter une fiche existante ;
- importer les données autorisées ;
- pousser certaines modifications ;
- suivre l’état de synchronisation ;
- suivre les informations de vérification disponibles ;
- journaliser chaque opération.

La validation d’une fiche Google reste sous le contrôle de Google.

## 6. LinkedIn

Le connecteur LinkedIn doit être conçu comme intégration conditionnelle.

Fonctions possibles selon permissions :

- rattacher une page entreprise ;
- lire certaines données autorisées ;
- synchroniser certaines métadonnées ;
- publier ou mettre à jour des contenus uniquement lorsque les scopes et produits API obtenus le permettent.

Jaxaay ne doit jamais dépendre de l’existence d’un droit d’écriture LinkedIn.

## 7. Facebook

Le connecteur Facebook est centré sur les Pages déjà administrées par le client.

Selon permissions disponibles :

- rattachement ;
- lecture de métadonnées ;
- synchronisation ;
- publication ou mise à jour autorisée.

La création automatique complète de pages n’est pas un prérequis Jaxaay.

## 8. YouTube

Fonctions envisagées :

- rattacher une chaîne ;
- importer identité et métadonnées autorisées ;
- afficher vidéos et playlists liées ;
- synchroniser les informations accessibles.

La création d’une chaîne ne fait pas partie du cycle ExternalProfiles.

## 9. TikTok

Fonctions envisagées :

- rattacher un profil ou compte Business ;
- récupérer les informations accessibles ;
- rattacher des contenus ;
- synchroniser certaines données lorsque les API disponibles le permettent.

## 10. Anti-doublons

Les identifiants externes participent au moteur de détection de doublons.

Signaux :

- provider + external_id
- nom normalisé
- site web
- téléphone
- adresse
- coordonnées GPS
- domaine
- organisation liée

Avant création d’une fiche, Jaxaay peut proposer une correspondance externe ou interne.

## 11. Conflits de données

Une donnée synchronisée doit conserver sa provenance.

Exemple :

```text
phone
├── value
├── source = jaxaay | google_business | linkedin | ...
├── synced_at
└── authority
```

La source externe ne doit pas écraser silencieusement une donnée validée manuellement.

## 12. Journal de synchronisation

Chaque opération conserve :

- provider
- external_profile_id
- direction
- operation
- status
- request metadata
- response metadata
- started_at
- completed_at
- error

## 13. Résilience

Les synchronisations sont exécutées par queue.

```text
Domain Event
→ Queue
→ Connector
→ Provider API
→ Sync Result
→ Audit Log
```

Une panne fournisseur ne doit pas bloquer Jaxaay.

## 14. Sécurité

Exigences :

- OAuth 2 lorsque disponible ;
- scopes minimaux ;
- tokens chiffrés ;
- rotation / révocation ;
- journalisation ;
- rate limiting ;
- aucun secret dans les logs ;
- permissions par organisation ;
- validation des callbacks ;
- protection contre replay lorsque pertinent.

## 15. API Jaxaay

Endpoints prévus :

```text
GET    /api/v1/entries/{entry}/external-profiles
POST   /api/v1/entries/{entry}/external-profiles
DELETE /api/v1/external-profiles/{profile}

GET    /api/v1/integrations/{provider}/authorize
GET    /api/v1/integrations/{provider}/callback

POST   /api/v1/external-profiles/{profile}/sync
GET    /api/v1/external-profiles/{profile}/sync-status
```

## 16. Administration

Le backend doit permettre :

- voir les connexions ;
- voir les scopes ;
- relancer une synchronisation ;
- suspendre un connecteur ;
- consulter les erreurs ;
- résoudre les conflits ;
- détacher un profil ;
- forcer une nouvelle correspondance ;
- auditer les changements.

## 17. Principe final

Jaxaay reste la source métier principale.

Les plateformes externes sont des sources ou canaux complémentaires :

```text
Jaxaay Core
    ↓
ExternalProfiles
    ↓
Providers
```

et jamais l’inverse.
