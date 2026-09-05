# Jaxaay Annuaire V2 — Guide de développement

**Version : 0.1**  
**Statut : Guide de référence pour le développement**

## 1. Objectif

Ce document définit les conventions de développement de Jaxaay Annuaire V2 afin de garantir cohérence, sécurité, maintenabilité, testabilité et stabilité de l’API.

Le dépôt GitHub constitue la source de vérité.

## 2. Stack cible

- PHP 8.3+
- Laravel 13
- Laravel Sanctum
- PostgreSQL
- Redis
- Laravel Queue / Scheduler
- Laravel Scout
- Livewire 4
- Tailwind CSS 4
- Flutter pour Android, iOS, Web, Windows et macOS

## 3. Documents à lire avant développement

```text
AGENTS.md
README.md
ROADMAP.md
docs/architecture/architecture.md
docs/architecture/database.md
docs/architecture/api.md
docs/security/security-architecture.md
```

## 4. Principe de conception

Toujours vérifier si le besoin peut être pris en charge par le moteur générique avant de créer une architecture spécifique.

Préférer :

```text
Directory
ListingType
FieldDefinition
FieldGroup
Taxonomy
Relationship
Location
ViewDefinition
SearchSchema
```

à une nouvelle table métier dédiée.

## 5. Séparation des couches

```text
HTTP / Controllers
        ↓
Application Services
        ↓
Domain Services
        ↓
Eloquent / Repositories
        ↓
PostgreSQL
```

Les contrôleurs restent fins.

## 6. Validation

Toute écriture importante utilise une `FormRequest`.

Exemples :

```text
StorePersonProfileRequest
StoreAnnouncementRequest
CreateClaimRequest
ExecuteImportRequest
```

La validation couvre types, relations, permissions, formats, tailles, champs dynamiques et repeaters.

## 7. Services métier

Exemples :

```text
PersonProfileService
DirectoryEntryService
ReferenceNumberService
ClaimService
VerificationService
LocationService
DuplicateDetectionService
ImportService
ExternalProfileService
```

La logique métier complexe ne doit pas être dispersée dans les contrôleurs.

## 8. Domain Events et Outbox

Événements possibles :

```text
DirectoryEntryPublished
PersonProfileVerified
ClaimApproved
AnnouncementPublished
ImportCompleted
DuplicateCandidateDetected
ExternalProfileLinked
```

Pour les effets critiques :

```text
Transaction métier
→ Outbox
→ Commit PostgreSQL
→ Queue
→ Traitement externe
```

## 9. Jobs et queues

À utiliser pour :

- imports / exports ;
- médias ;
- géocodage ;
- indexation ;
- notifications ;
- synchronisations externes ;
- traitements IA ;
- dédoublonnage lourd.

Un job doit être idempotent, retryable et observable.

## 10. Multi-tenant

Toute ressource liée à une organisation est filtrée côté serveur.

Le header `X-Organization-Id` résout le contexte mais ne constitue jamais une autorisation.

Les contrôles sont appliqués via :

- middleware ;
- Policies ;
- services ;
- requêtes ;
- tests.

## 11. Policies

Policies attendues notamment pour :

```text
PersonProfilePolicy
DirectoryEntryPolicy
AnnouncementPolicy
DocumentPolicy
ClaimPolicy
ImportPolicy
ExternalProfilePolicy
```

L’autorisation combine :

```text
User
+ Organization
+ Role / Permission
+ Ownership
+ Entitlement
+ Status
```

## 12. Entitlements et quotas

Les droits SaaS sont contrôlés côté serveur.

Exemples :

```text
announcement.publish
external_profiles
advanced_analytics
featured_listing
```

Quotas possibles :

```text
listings_limit
announcements_limit
users_limit
media_limit
imports_limit
featured_limit
```

## 13. Migrations PostgreSQL

Avant chaque migration :

1. besoin validé ;
2. modèle documenté ;
3. indexes définis ;
4. contraintes étudiées ;
5. impact analysé.

Utiliser lorsque pertinent :

- foreign keys ;
- unique indexes ;
- partial indexes ;
- check constraints ;
- transactions ;
- locks.

Ne pas utiliser JSONB pour éviter de modéliser une donnée métier importante.

## 14. Champs dynamiques

Les champs utilisés pour recherche, filtres, tri ou relations doivent rester structurés et requêtables.

Éviter un unique blob JSON contenant toutes les valeurs métier.

## 15. Fieldsets / Repeaters

Une occurrence de repeater est stockée individuellement.

Exemple :

```text
Experiences
├── item 1
├── item 2
└── item 3
```

Chaque item conserve sa position, ses valeurs et ses relations.

## 16. Taxonomies

Les taxonomies structurantes sont contrôlées.

```text
Suggestion
→ Review
→ Approved / Merged / Rejected
```

Un formulaire client ne crée jamais directement un terme canonique.

## 17. Location Core

Tous les annuaires utilisent le même référentiel géographique.

Ne pas créer des référentiels séparés par vertical.

## 18. Références métier

La génération passe par `ReferenceNumberService`.

Exemples :

```text
PF2609-0001
AN2609-0001
DOC2609-0001
```

Une référence est unique, transactionnelle, immuable et jamais recyclée.

## 19. API Resources

Les réponses passent par des Resources dédiées :

```text
PersonProfileResource
BusinessResource
AnnouncementResource
DocumentResource
SearchResultResource
```

Les données privées ne sont jamais simplement masquées côté frontend : elles ne sont pas retournées.

## 20. API

Base :

```text
/api/v1
```

Une rupture incompatible nécessite une nouvelle version.

Codes HTTP principaux :

```text
200  OK
201  Created
202  Accepted
204  No Content
401  Unauthorized
403  Forbidden
404  Not Found
409  Conflict
422  Unprocessable Entity
429  Too Many Requests
```

## 21. Recherche

PostgreSQL reste la source de vérité.

```text
PostgreSQL
→ Outbox
→ Queue
→ Laravel Scout
→ Typesense / Meilisearch
```

Ne jamais écrire une donnée métier uniquement dans le moteur de recherche.

Ne jamais indexer :

- contacts privés ;
- KYC ;
- notes internes ;
- secrets.

## 22. DataExchange

Pipeline :

```text
Staging
→ Normalisation
→ Validation
→ Dédoublonnage
→ Mapping
→ Domain Service
→ Persist
→ Indexation
```

Un import ne doit pas écrire directement en base en contournant les règles métier.

## 23. Dédoublonnage

États :

```text
possible
probable
confirmed
not_duplicate
merged
```

La fusion critique reste humaine et auditée.

## 24. External Profiles

Chaque fournisseur implémente un connecteur séparé.

Conceptuellement :

```php
interface ExternalProfileConnector
{
    public function search(...);
    public function pull(...);
    public function push(...);
    public function sync(...);
}
```

Les appels externes utilisent timeout, retry contrôlé, logs sans secret et gestion des erreurs.

## 25. Médias

Utiliser une couche média commune :

- upload ;
- validation ;
- checksum ;
- variantes ;
- stockage ;
- visibilité ;
- import ZIP.

## 26. Cache

Le cache accélère mais ne devient jamais source de vérité.

Candidats :

- taxonomies ;
- locations ;
- schémas d’annuaire ;
- pages publiques.

Prévoir invalidation explicite.

## 27. Logs et audit

Les logs techniques sont distincts de l’audit métier.

Audit prioritaire :

```text
profile.updated
claim.approved
location.merged
taxonomy.changed
import.executed
external_profile.synced
```

## 28. Tests

Types attendus :

- Unit ;
- Feature ;
- Integration ;
- Regression.

Chaque domaine multi-tenant doit démontrer qu’une organisation A ne peut pas accéder aux ressources d’une organisation B.

Tester notamment :

- visibilité des contacts ;
- KYC non exposé ;
- imports ;
- recherche ;
- synchronisations externes ;
- références ;
- quotas ;
- entitlements.

## 29. Factories et seeders

Les factories utilisent des données fictives.

Seeders possibles :

- rôles ;
- plans de test ;
- entitlements ;
- taxonomies minimales ;
- localités de développement.

Ne pas utiliser de données personnelles réelles.

## 30. Enums

Utiliser des enums PHP pour les états stables.

Exemples :

```text
EntryStatus
VerificationStatus
ClaimStatus
Visibility
RelationshipType
```

Éviter les chaînes magiques dispersées.

## 31. Transactions et concurrence

Utiliser transaction, locks ou contraintes pour :

- génération de références ;
- quotas ;
- claims ;
- fusion ;
- imports complexes ;
- idempotence.

## 32. Git

Branches recommandées :

```text
main
develop
feature/*
fix/*
docs/*
refactor/*
```

Messages de commit :

```text
feat(profile): add professional affiliations
feat(search): add location facets
fix(import): prevent duplicate references
docs(api): document profile visibility
test(tenant): cover cross-organization access
```

## 33. Pull Requests

Une PR importante précise :

- contexte ;
- objectif ;
- solution ;
- fichiers principaux ;
- migrations ;
- endpoints ;
- sécurité ;
- tests ;
- documentation ;
- risques.

## 34. CHANGELOG

Le changelog contient uniquement les changements réellement intégrés.

Les fonctions futures restent dans `ROADMAP.md`.

## 35. ADR

Créer un ADR pour les décisions :

- structurantes ;
- difficiles à inverser ;
- transversales ;
- liées au modèle de données ;
- liées à une dépendance majeure.

## 36. Workflow Codex

Avant codage :

1. lire `AGENTS.md` ;
2. lire les documents concernés ;
3. inspecter le dépôt ;
4. identifier l’existant ;
5. proposer un plan limité ;
6. modifier ;
7. tester ;
8. produire un rapport.

Rapport attendu :

```text
Objectif
Fichiers modifiés
Décisions techniques
Migrations
Tests exécutés
Résultats
Risques / limites
Documentation mise à jour
Prochaine étape
```

## 37. Définition de Done

Une fonctionnalité majeure est terminée lorsque :

```text
Code
+ Tests
+ Sécurité
+ Documentation
+ Validation
```

sont réalisés.

Un endpoint qui fonctionne uniquement en test manuel n’est pas suffisant.

## 38. Checklist avant merge

```text
[ ] architecture respectée
[ ] tests ajoutés
[ ] suite existante verte
[ ] isolation tenant vérifiée
[ ] données privées protégées
[ ] migrations revues
[ ] indexes vérifiés
[ ] API cohérente
[ ] documentation mise à jour
[ ] changelog mis à jour si réellement intégré
```

## 39. Principe final

Jaxaay doit privilégier :

```text
simplicité
+ généricité maîtrisée
+ performance
+ sécurité
+ testabilité
+ documentation
```

et éviter :

```text
duplication
+ logique métier dans les contrôleurs
+ tables verticales inutiles
+ JSON opaque
+ contournement des Policies
+ dépendance forte aux fournisseurs externes
```
