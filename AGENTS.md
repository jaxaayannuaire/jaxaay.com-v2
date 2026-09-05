# AGENTS.md — Jaxaay Annuaire V2

**Version : 0.1**
**Statut : Règles de gouvernance du développement**

Ce fichier définit les règles applicables aux agents IA et contributeurs intervenant sur **Jaxaay Annuaire V2**.

---

# 1. Principe général

Le dépôt GitHub constitue la **source de vérité technique**.

Une information discutée dans ChatGPT n'est pas considérée comme implémentée tant qu'elle n'existe pas dans le dépôt et n'a pas été validée.

Workflow :

```text
Étude
  ↓
Décision
  ↓
Documentation
  ↓
Implémentation
  ↓
Tests
  ↓
Validation
  ↓
Changelog
```

---

# 2. Rôles

## ChatGPT — Tech Lead

Responsabilités :

* architecture ;
* conception fonctionnelle ;
* arbitrages techniques ;
* préparation des spécifications ;
* analyse des rapports Codex ;
* définition des étapes de développement ;
* maintien de la cohérence globale.

ChatGPT ne doit jamais considérer une modification comme réalisée uniquement parce qu'elle a été proposée.

---

## Codex — Développement

Codex intervient directement sur le dépôt.

Responsabilités :

* création et modification du code ;
* migrations ;
* modèles ;
* services ;
* API ;
* tests ;
* refactoring ;
* documentation technique liée à l'implémentation.

Avant toute modification importante, Codex doit consulter :

```text
AGENTS.md
README.md
ROADMAP.md
docs/architecture/architecture.md
```

et les documents spécifiques au domaine concerné.

---

## ChatGPT Work — Documentation / analyse

Responsabilités principales :

* analyse documentaire ;
* études techniques ;
* documentation longue ;
* plans de tests ;
* audits ;
* comparaison architecture ↔ code ;
* procédures ;
* préparation de spécifications.

Work ne doit pas inventer l'état du dépôt.

---

# 3. Langue

La documentation du projet est rédigée principalement en **français**.

Le code conserve les conventions techniques usuelles en anglais :

```text
Models
Services
Controllers
Methods
Variables
Database fields
API routes
Events
Jobs
Enums
```

Exemple :

```php
class PersonProfileService
{
    public function createProfile(): PersonProfile
}
```

---

# 4. Architecture de référence

Jaxaay utilise un Core SaaS inspiré de Yessal ERP :

```text
User
 ↓
Organization
 ↓
Subscription
 ↓
Plan
 ↓
Modules
 ↓
Entitlements
 ↓
Quotas
```

Les fonctionnalités d'annuaire constituent des domaines métier construits au-dessus de ce Core.

---

# 5. Principe du Directory Engine

Ne pas créer un modèle spécifique pour chaque nouveau secteur lorsque le moteur générique peut le prendre en charge.

Privilégier :

```text
DirectoryType
ListingType
FieldDefinition
FieldValue
Taxonomy
Relationship
ViewDefinition
SearchSchema
```

avant de créer des tables ou composants métier spécialisés.

---

# 6. Entités métier importantes

Les concepts suivants doivent rester distincts :

```text
User
PersonProfile
Organization
Listing
Announcement
Document
Location
```

En particulier :

```text
User != PersonProfile
Organization != Listing
```

Une personne publique peut posséder une fiche sans avoir de compte Jaxaay.

---

# 7. Location Core

Il existe **un seul référentiel géographique** pour toute la plateforme.

Ne jamais créer des tables de localités séparées par annuaire.

Hiérarchie cible :

```text
Country
Region
Department
Commune
District / Neighborhood
```

Les contenus métier doivent référencer ces entités.

---

# 8. Taxonomies contrôlées

Les utilisateurs ne doivent pas créer librement :

* catégories principales ;
* professions de référence ;
* types de contenus ;
* localités ;
* taxonomies structurantes.

Ils peuvent proposer de nouvelles valeurs.

Workflow :

```text
Suggestion
→ Review
→ Validation
→ Référentiel
```

L'objectif est d'éviter :

```text
Ophtalmologue
Ophtalmo
Ophtalmologue médical
Ophthalmologue
```

pour une même notion.

---

# 9. Références métier

Les entités publiées doivent recevoir une référence stable.

Format initial :

```text
PREFIX + YYMM + sequence
```

Exemples :

```text
PF2609-0001
AN2609-0001
DOC2609-0001
```

Une référence déjà attribuée ne doit jamais être recyclée.

---

# 10. Données personnelles

Les coordonnées des `PersonProfile` sont privées par défaut.

Les niveaux de visibilité doivent être gérés explicitement.

Exemples :

```text
private
public
authenticated
organization
owner_only
```

Aucun endpoint ne doit exposer une donnée privée uniquement parce qu'elle existe en base.

---

# 11. Validation des profils

Les profils sensibles ou institutionnels ne doivent jamais être publiés automatiquement.

Exemples :

* responsables politiques ;
* médecins ;
* professions réglementées ;
* scientifiques ;
* universitaires ;
* religieux ;
* personnalités publiques.

Workflow attendu :

```text
draft
→ pending_review
→ published
→ verified
```

La propriété d'une fiche (`claimed`) et sa vérification (`verified`) sont deux notions distinctes.

---

# 12. Relations inter-entités

Les relations doivent être explicites.

Exemples :

```text
PersonProfile
  ↕
ProfessionalAffiliation
  ↕
Organization
```

et :

```text
Document
 ↔ PersonProfile

Document
 ↔ Organization
```

Éviter de dupliquer dans des champs texte des relations pouvant être modélisées proprement.

---

# 13. Champs dynamiques

Le moteur doit supporter :

* champs standards ;
* champs conditionnels ;
* relations ;
* fieldsets ;
* repeaters.

Les repeaters doivent être conçus comme des données structurées exploitables par la recherche et l'API.

Ne pas stocker arbitrairement des structures métier importantes dans un blob JSON uniquement pour éviter leur modélisation.

---

# 14. Recherche

La recherche est une fonctionnalité critique.

Elle doit permettre une recherche transversale :

```text
PersonProfiles
Organizations
Listings
Announcements
Documents
Locations
```

Exemple :

```text
ophtalmologue kaolack
```

doit pouvoir classer :

1. profils professionnels ;
2. établissements ;
3. organisations ;
4. contenus associés.

Le moteur doit rester compatible avec Laravel Scout et un moteur externe.

---

# 15. Import / Export

Toute fonctionnalité importante doit être compatible, lorsque pertinent, avec le module `DataExchange`.

Les imports doivent prévoir :

* validation ;
* dry-run ;
* rapport d'erreurs ;
* traitement par lots ;
* idempotence ;
* détection des doublons ;
* logs ;
* rollback lorsque possible.

Un import massif ne doit pas contourner les règles métier.

---

# 16. API-first

Toutes les fonctionnalités importantes doivent être utilisables via API lorsque cela est pertinent.

Clients prévus :

```text
Web
Flutter
Telegram Bot
External integrations
Future agents
```

Le frontend ne doit pas devenir la seule couche capable d'exécuter une opération métier.

---

# 17. Telegram Bot

Le bot Telegram sera développé comme projet séparé.

Architecture :

```text
Telegram Bot
    ↓
Jaxaay API
    ↓
Domain Services
    ↓
Database
```

Le bot ne doit jamais accéder directement à PostgreSQL.

---

# 18. External Profiles

Les intégrations externes doivent utiliser des connecteurs indépendants.

```text
ExternalProfileProvider

GoogleBusinessConnector
LinkedInConnector
FacebookConnector
YouTubeConnector
TikTokConnector
```

Les limitations d'un fournisseur ne doivent pas contaminer le modèle métier interne.

---

# 19. IA et automatisation

Les agents IA peuvent :

* proposer une catégorie ;
* détecter un doublon probable ;
* proposer une correction ;
* proposer une relation ;
* analyser des données importées ;
* suggérer une migration.

Ils ne doivent pas automatiquement :

* valider une personnalité sensible ;
* supprimer une fiche ;
* fusionner des fiches importantes ;
* modifier un référentiel structurant sans règle explicite.

Les décisions sensibles restent humaines.

---

# 20. Multi-tenant

Toute ressource liée à une organisation doit être correctement isolée.

Les contrôles doivent être réalisés au niveau :

* middleware ;
* Policies ;
* requêtes ;
* services ;
* contrôleurs ;
* tests.

Ne jamais se fier uniquement à un `organization_id` fourni par le client.

---

# 21. Sécurité

Toute nouvelle fonctionnalité doit considérer :

* authentification ;
* autorisation ;
* isolation tenant ;
* validation des entrées ;
* rate limiting ;
* uploads ;
* audit ;
* protection des secrets ;
* données personnelles ;
* abus et spam.

---

# 22. Tests

Toute fonctionnalité métier majeure doit posséder des tests automatisés.

Priorités :

```text
Feature tests
Domain tests
Authorization tests
Tenant isolation tests
Import tests
Search tests
API tests
```

Un correctif de bug doit idéalement ajouter un test reproduisant le bug.

---

# 23. Base de données

Toute migration doit :

* avoir un objectif métier documenté ;
* préserver l'intégrité référentielle ;
* créer les index nécessaires ;
* éviter les colonnes redondantes ;
* utiliser des contraintes PostgreSQL lorsque pertinent.

Ne pas créer une migration exploratoire sur la branche principale.

---

# 24. Performance

Éviter notamment :

* N+1 queries ;
* chargement complet de grandes collections ;
* filtres PHP lorsque PostgreSQL peut les faire ;
* traitements lourds dans les requêtes HTTP ;
* images originales inutilement lourdes.

Utiliser lorsque pertinent :

```text
Indexes
Caching
Queues
Pagination
Cursor pagination
Lazy loading
Search engine
```

---

# 25. Git

Branches recommandées :

```text
main
develop
feature/*
fix/*
docs/*
refactor/*
```

`main` doit correspondre à une version stable.

Le développement courant se fait sur `develop` ou sur une branche dédiée.

---

# 26. Commits

Messages explicites et atomiques.

Exemples :

```text
feat(profile): add professional affiliations
feat(search): add profile location facets
fix(import): prevent duplicate reference creation
docs(architecture): document location core
test(profile): cover private contact visibility
```

---

# 27. Pull Requests

Une PR importante doit préciser :

* objectif ;
* fichiers principaux ;
* migrations ;
* API ajoutée/modifiée ;
* tests ;
* impacts sécurité ;
* impacts architecture ;
* documentation mise à jour.

---

# 28. Documentation obligatoire

Après une évolution majeure, vérifier :

```text
README.md
ROADMAP.md
CHANGELOG.md
docs/architecture/
docs/functional/
docs/decisions/
```

Ne pas modifier ces fichiers artificiellement si aucun changement ne les concerne.

---

# 29. CHANGELOG

Le changelog décrit uniquement les changements réellement intégrés.

Ne pas écrire une fonctionnalité dans `CHANGELOG.md` lorsqu'elle est seulement planifiée.

Les fonctionnalités futures appartiennent à `ROADMAP.md`.

---

# 30. ADR

Créer un ADR lorsqu'une décision :

* structure plusieurs domaines ;
* est difficile à inverser ;
* introduit une dépendance majeure ;
* modifie le modèle de données ;
* modifie une convention centrale.

Format :

```text
Contexte
Décision
Alternatives
Conséquences
Statut
```

---

# 31. Compatibilité future WaPASTEF

Le moteur Jaxaay doit rester suffisamment générique pour permettre une future migration de l'annuaire AGPS/WaPASTEF.

Cela ne signifie pas intégrer des règles politiques ou spécifiques à WaPASTEF dans le Core Jaxaay.

Principe :

```text
Jaxaay Engine
      ↓
Directory Blueprint
      ↓
AGPS / autres projets
```

---

# 32. Marketplace

La marketplace n'est pas prioritaire pour V1.

Ne pas introduire prématurément :

* panier ;
* commande ;
* stock ;
* wallet ;
* commission ;
* livraison.

L'architecture doit seulement éviter de bloquer leur ajout futur.

---

# 33. Règle finale

Avant toute implémentation importante :

1. comprendre le besoin ;
2. consulter l'architecture ;
3. vérifier qu'un composant générique n'existe pas déjà ;
4. définir les impacts ;
5. coder ;
6. tester ;
7. documenter ;
8. produire un rapport précis.
