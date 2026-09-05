# AGENTS.md — Jaxaay Annuaire V2

**Version : 0.2**  
**Statut : Règles de gouvernance du dépôt**  
**Projet : Jaxaay Annuaire V2**

## 1. Objectif

Ce document définit les règles que tout agent, développeur ou outil automatisé doit respecter avant de modifier le dépôt Jaxaay Annuaire V2.

Le dépôt GitHub constitue la source de vérité.

Une fonctionnalité discutée, spécifiée ou proposée n'est considérée comme implémentée que lorsqu'elle :

1. existe réellement dans le dépôt ;
2. respecte l'architecture validée ;
3. possède les tests nécessaires ;
4. a été vérifiée ;
5. est documentée si nécessaire.

Cycle de référence :

```text
Étude
→ Décision
→ Documentation
→ Implémentation
→ Tests
→ Validation
→ Changelog
```

---

## 2. Langue du projet

### Documentation et communication

Tout le contenu suivant doit être rédigé en **français** :

- documentation ;
- rapports Codex ;
- descriptions de Pull Request ;
- descriptions d'Issues ;
- commentaires de revue ;
- notes d'architecture ;
- ADR ;
- changelog ;
- roadmap ;
- messages de commit ;
- résumés de modifications ;
- rapports de tests.

### Code

Les éléments techniques restent en **anglais** lorsque cela correspond aux conventions de développement :

- noms de classes ;
- méthodes ;
- variables ;
- enums ;
- tables ;
- colonnes ;
- endpoints ;
- namespaces ;
- noms de fichiers applicatifs ;
- messages internes destinés aux développeurs lorsque pertinent.

Exemples :

```text
PersonProfile
OrganizationProfile
DirectoryEntry
ReferenceNumberService
StorePersonProfileRequest
```

### Commits

Les préfixes Conventional Commits restent en anglais pour compatibilité avec l'écosystème Git :

```text
feat
fix
docs
test
refactor
chore
perf
build
ci
revert
```

Le périmètre et la description doivent être rédigés en français.

Format :

```text
<type>(<périmètre>): <description en français>
```

Exemples :

```text
feat(profils): ajouter les affiliations professionnelles
feat(recherche): ajouter les facettes géographiques
fix(import): empêcher la création de références en doublon
docs(api): documenter la visibilité des contacts privés
test(tenant): couvrir l'isolation entre organisations
refactor(annuaire): extraire le résolveur de champs
chore(dépendances): mettre à jour les dépendances Laravel
```

---

## 3. Rôles

### ChatGPT — Tech Lead

Responsabilités :

- architecture globale ;
- conception fonctionnelle ;
- arbitrages techniques ;
- spécifications ;
- analyse des rapports Codex ;
- cohérence entre documentation et implémentation ;
- préparation des étapes de développement ;
- validation des changements structurants.

ChatGPT ne doit pas déclarer une fonctionnalité implémentée uniquement parce qu'elle a été conçue.

### Codex — Implémentation

Responsabilités :

- inspection du dépôt ;
- développement ;
- migrations ;
- modèles ;
- services ;
- API ;
- tests ;
- refactoring ;
- documentation liée aux changements réels ;
- rapports de recette technique.

Codex ne doit jamais inventer l'état du dépôt.

### ChatGPT Work

Responsabilités privilégiées :

- documentation longue ;
- audits ;
- études techniques ;
- plans de tests ;
- procédures ;
- QA documentaire ;
- analyse de logs volumineux ;
- préparation de spécifications.

---

## 4. Lecture obligatoire avant modification

Avant toute modification significative, lire au minimum :

```text
AGENTS.md
README.md
ROADMAP.md
CHANGELOG.md
docs/architecture/architecture.md
docs/architecture/database.md
docs/architecture/api.md
docs/security/security-architecture.md
docs/development/development-guide.md
```

Lire également :

- le document fonctionnel du domaine concerné ;
- les ADR applicables ;
- les tests existants associés ;
- les migrations existantes avant toute création de nouvelle migration.

---

## 5. Principes d'architecture fondamentaux

### Core SaaS

Architecture cible :

```text
User
→ Organization
→ Subscription
→ Plan
→ Modules
→ Entitlements
→ Quotas
```

### Directory Engine

Architecture conceptuelle :

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

Toujours vérifier qu'un nouveau besoin peut être représenté par ce moteur générique avant de créer une table ou un domaine spécifique.

---

## 6. Séparations conceptuelles obligatoires

Respecter impérativement :

```text
User != PersonProfile
Organization != OrganizationProfile
```

Un utilisateur est un compte d'accès.

Un `PersonProfile` est une fiche publique ou semi-publique représentant une personne physique.

Une `Organization` est un tenant SaaS.

Un `OrganizationProfile` est une fiche publique d'entreprise, d'établissement, d'association, d'institution ou d'organisation.

---

## 7. DirectoryEntry

Les sous-annuaires partagent une couche publique commune :

```text
DirectoryEntry
├── PersonProfile
├── OrganizationProfile
├── Announcement
└── Document
```

Cette couche centralise notamment :

- référence métier ;
- statut ;
- visibilité ;
- recherche ;
- SEO ;
- relations ;
- modération ;
- favoris ;
- claims ;
- vérification ;
- sources ;
- audit.

---

## 8. Location Core

Un seul référentiel de localités doit être utilisé par tous les domaines.

Architecture cible :

```text
Country
→ Region
→ Department
→ Commune
→ District / Neighborhood
```

Interdictions :

- créer un référentiel géographique indépendant par vertical ;
- autoriser un utilisateur à créer librement une localité canonique ;
- fusionner automatiquement des localités critiques sans validation.

Les utilisateurs peuvent proposer une correction ou une nouvelle valeur.

---

## 9. Taxonomies contrôlées

Les taxonomies structurantes ne sont pas créées librement par les clients.

Exemples :

- professions ;
- spécialités ;
- catégories principales ;
- types d'organisation ;
- types de documents ;
- localités structurantes.

Workflow :

```text
Suggestion
→ Review
→ Approved / Merged / Rejected
```

Une IA peut suggérer, jamais valider seule une taxonomie structurante.

---

## 10. Champs dynamiques, fieldsets et repeaters

Le moteur doit prendre en charge :

- champs configurables ;
- conditions d'affichage ;
- fieldsets ;
- repeaters ;
- champs relationnels ;
- visibilité par champ ;
- filtres ;
- tri ;
- recherche.

Ne pas remplacer toute la modélisation métier par un unique blob JSON.

Les données utilisées pour :

- recherche ;
- filtres ;
- tri ;
- relations ;
- analytics ;

doivent rester structurées et requêtables.

---

## 11. PersonProfile

Règles principales :

- une personne peut être référencée sans compte Jaxaay ;
- un profil sensible ne doit pas être auto-validé ;
- `claimed` et `verified` sont deux états distincts ;
- les coordonnées personnelles sont privées par défaut ;
- les affiliations à des organisations sont explicites ;
- les sources et preuves doivent être conservables.

Les profils sensibles incluent notamment :

- responsables politiques ;
- élus ;
- professions réglementées ;
- médecins ;
- avocats ;
- scientifiques / universitaires selon contexte ;
- responsables religieux ;
- personnalités publiques ;
- profils institutionnels sensibles.

---

## 12. Données personnelles

Les données personnelles sont privées par défaut.

Exemples :

- téléphone ;
- email ;
- WhatsApp ;
- adresse privée ;
- documents d'identité ;
- données KYC.

Visibilités possibles :

```text
public
authenticated
organization
owner_only
private
```

Une donnée non autorisée ne doit jamais être envoyée dans une réponse API puis simplement masquée côté interface.

---

## 13. Relations inter-annuaires

Les relations doivent être explicites et typées.

Exemples :

```text
PersonProfile
→ ProfessionalAffiliation
→ OrganizationProfile
```

ou :

```text
Document
→ RelatedTo
→ PersonProfile
```

Une relation peut contenir :

- type ;
- rôle ;
- date de début ;
- date de fin ;
- caractère actuel ;
- priorité ;
- source ;
- statut de vérification.

---

## 14. Références métier

Chaque fiche publiée peut recevoir une référence humaine stable.

Format :

```text
PREFIX + YYMM + sequence
```

Exemples :

```text
PF2609-0001
AN2609-0001
DOC2609-0001
```

Règles :

- unique ;
- immuable ;
- jamais recyclée ;
- générée transactionnellement ;
- distincte de l'ID interne ;
- distincte du `public_id`.

---

## 15. Claims et vérification

Principe :

```text
claimed != verified
```

Un claim approuvé signifie qu'un compte ou une organisation est autorisé à gérer une fiche.

Une vérification signifie que Jaxaay a validé une information selon un processus défini.

Les opérations KYC et de vérification sensible sont auditées.

---

## 16. Recherche

PostgreSQL reste la source de vérité.

Le moteur de recherche externe est un index dérivé.

Architecture :

```text
PostgreSQL
→ Outbox
→ Queue
→ Laravel Scout
→ Typesense / Meilisearch
```

Le choix Typesense vs Meilisearch doit être validé par benchmark et ADR.

Ne jamais indexer :

- contacts privés ;
- KYC ;
- notes internes ;
- secrets ;
- données non publiables.

---

## 17. DataExchange

`DataExchange` est prioritaire en V1.

Fonctions attendues :

- import CSV ;
- export CSV ;
- médias ZIP ;
- Blueprints JSON ;
- mapping ;
- dry-run ;
- validation ;
- normalisation ;
- dédoublonnage ;
- traitement par lot ;
- journal des erreurs ;
- reprise ;
- rollback lorsque possible.

Un import ne doit jamais contourner les services métier, Policies, taxonomies contrôlées ou Location Core.

---

## 18. Dédoublonnage et fusion

L'IA ou des scripts peuvent proposer :

- doublon probable ;
- correction ;
- rapprochement ;
- fusion.

Une fusion critique reste humaine.

Les fusions doivent conserver :

- audit ;
- ancienne référence ;
- relations ;
- historique ;
- traçabilité.

---

## 19. External Profiles

Les fournisseurs externes sont isolés par connecteur :

```text
GoogleBusinessConnector
LinkedInConnector
FacebookConnector
YouTubeConnector
TikTokConnector
```

Règles :

- OAuth lorsque disponible ;
- scopes minimaux ;
- tokens chiffrés ;
- aucune dépendance métier forte aux limitations d'un fournisseur ;
- erreurs et synchronisations auditées.

Une panne externe ne doit jamais bloquer Jaxaay Core.

---

## 20. Telegram Bot

Le futur bot Telegram est un client de l'API.

Architecture :

```text
Telegram
→ Bot
→ Jaxaay API
→ Domain Services
→ PostgreSQL
```

Interdiction absolue :

```text
Bot → PostgreSQL direct
```

---

## 21. IA

L'IA peut :

- proposer une catégorie ;
- proposer une profession ;
- détecter un doublon ;
- suggérer une relation ;
- proposer une localisation ;
- produire un résumé ;
- classifier un document ;
- signaler une incohérence.

L'IA ne peut pas automatiquement :

- valider un KYC ;
- vérifier une identité ;
- vérifier une profession réglementée ;
- publier un profil sensible ;
- fusionner deux personnalités ;
- supprimer définitivement une fiche ;
- modifier une taxonomie critique ;
- modifier une localité canonique critique.

---

## 22. Marketplace

La marketplace transactionnelle est hors périmètre V1.

Ne pas créer sans décision explicite :

- panier ;
- commandes ;
- stock ;
- wallet ;
- commissions ;
- payouts ;
- livraison ;
- catalogue transactionnel.

---

## 23. Multi-tenant

Toute ressource tenantée doit être isolée côté serveur.

Le header :

```text
X-Organization-Id
```

sert uniquement à résoudre le contexte.

Il ne constitue jamais une autorisation.

Toute ressource concernée doit être protégée via :

- middleware ;
- Policies ;
- services ;
- requêtes tenant-scopées ;
- tests automatisés.

---

## 24. Sécurité

Exigences générales :

- aucune donnée sensible dans Git ;
- aucun `.env` committé ;
- aucun token dans les logs ;
- validation côté serveur ;
- uploads contrôlés ;
- rate limiting ;
- audit des opérations sensibles ;
- secrets chiffrés ou injectés par environnement ;
- aucune donnée KYC dans le moteur de recherche ;
- aucun accès direct PostgreSQL depuis un client.

---

## 25. Migrations

Avant toute migration :

1. lire `docs/architecture/database.md` ;
2. vérifier l'existant ;
3. confirmer le besoin ;
4. vérifier les indexes ;
5. définir les foreign keys ;
6. analyser les contraintes ;
7. éviter la duplication.

Ne jamais créer une migration exploratoire si le modèle n'est pas validé.

---

## 26. Tests

Toute fonctionnalité importante doit inclure les tests adaptés.

Types :

- unit ;
- feature ;
- integration ;
- regression.

Cas obligatoires selon domaine :

- isolation tenant ;
- autorisation ;
- visibilité ;
- contacts privés ;
- quotas ;
- entitlements ;
- imports ;
- recherche ;
- idempotence ;
- données sensibles.

Une fonctionnalité n'est pas considérée terminée si elle n'est validée que manuellement.

---

## 27. Git

Branches recommandées :

```text
main
develop
feature/*
fix/*
docs/*
refactor/*
```

- `main` : stable / production ;
- `develop` : intégration ;
- branches dédiées pour les changements.

Ne pas effectuer de `force push` sur `main` ou `develop`.

---

## 28. Règles de commit

Tous les messages de commit sont en français, à l'exception du préfixe Conventional Commit.

Exemples :

```text
feat(profils): ajouter les affiliations professionnelles
fix(recherche): corriger le filtrage par commune
docs(architecture): documenter le moteur de relations
test(tenant): ajouter les tests d'isolation des organisations
refactor(api): simplifier la résolution du contexte organisation
```

Éviter :

```text
update stuff
fix bug
changes
wip
```

Un commit doit représenter une modification cohérente.

---

## 29. Pull Requests

Toute Pull Request doit être rédigée en français.

Elle doit décrire :

- contexte ;
- objectif ;
- solution ;
- fichiers principaux ;
- migrations ;
- endpoints ;
- sécurité ;
- tests ;
- documentation ;
- risques ;
- limitations.

---

## 30. CHANGELOG

Le changelog contient uniquement les changements réellement intégrés.

Ne pas inscrire sous `Added` une fonctionnalité seulement planifiée.

Les fonctions futures restent dans :

```text
ROADMAP.md
```

---

## 31. ADR

Créer un ADR pour toute décision :

- structurante ;
- transversale ;
- difficile à inverser ;
- liée au modèle de données ;
- liée à un composant majeur ;
- liée à une technologie de recherche, stockage ou infrastructure.

Les ADR sont rédigés en français.

---

## 32. Documentation

Après une évolution majeure, vérifier :

```text
README.md
ROADMAP.md
CHANGELOG.md
docs/architecture/
docs/functional/
docs/security/
docs/development/
docs/deployment/
docs/decisions/
```

Ne modifier que les documents concernés.

---

## 33. Workflow Codex obligatoire

Avant toute modification, Codex doit :

1. lire les documents applicables ;
2. vérifier la branche ;
3. vérifier le HEAD ;
4. vérifier le working tree ;
5. inspecter les fichiers concernés ;
6. lire les tests associés ;
7. confirmer la cause ou le besoin ;
8. appliquer une modification limitée ;
9. exécuter les tests ;
10. produire un rapport en français.

Codex ne doit pas :

- inventer l'état du dépôt ;
- déclarer un test réussi sans l'avoir exécuté ;
- modifier des domaines hors périmètre ;
- faire du nettoyage opportuniste non demandé ;
- ajouter une dépendance sans justification ;
- stage ;
- commit ;
- push ;

sauf demande explicite de l'utilisateur.

---

## 34. Rapport Codex

Tout rapport Codex est rédigé en français.

Format recommandé :

```text
Objectif
Baseline
Cause / constat
Fichiers modifiés
Décisions techniques
Migrations
Tests exécutés
Résultats
Sécurité
Documentation mise à jour
Risques / limites
État Git final
GO / NO-GO
Prochaine étape
```

---

## 35. Définition de Done

Une fonctionnalité majeure est terminée lorsque :

```text
Code
+ Tests
+ Sécurité
+ Documentation
+ Validation
```

sont réalisés.

Un endpoint fonctionnel sans tests ni validation de sécurité n'est pas terminé.

---

## 36. Principe final

Jaxaay Annuaire V2 doit privilégier :

```text
simplicité
+ généricité maîtrisée
+ performance
+ sécurité
+ testabilité
+ traçabilité
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
+ modifications non documentées
+ commits imprécis
```
