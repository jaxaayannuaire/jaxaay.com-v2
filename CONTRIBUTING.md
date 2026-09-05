# CONTRIBUTING.md — Contribuer à Jaxaay Annuaire V2

**Version : 0.2**  
**Projet : Jaxaay Annuaire V2**

## 1. Objet

Ce document décrit les règles de contribution au projet Jaxaay Annuaire V2.

Avant toute contribution, lire obligatoirement :

```text
AGENTS.md
README.md
ROADMAP.md
docs/architecture/architecture.md
docs/architecture/database.md
docs/architecture/api.md
docs/security/security-architecture.md
docs/development/development-guide.md
```

Les documents fonctionnels et ADR du domaine concerné doivent également être consultés.

---

## 2. Langue

Tout le contenu de gouvernance et de collaboration est rédigé en **français** :

- messages de commit ;
- Issues ;
- Pull Requests ;
- commentaires de revue ;
- rapports de tests ;
- documentation ;
- ADR ;
- changelog ;
- roadmap.

Le code et les identifiants techniques restent en anglais lorsque cela correspond aux conventions Laravel, PHP, PostgreSQL, Flutter ou API.

---

## 3. Branches

Branches principales :

```text
main
develop
```

Branches de travail :

```text
feature/*
fix/*
docs/*
refactor/*
```

Exemples :

```text
feature/profils-affiliations
feature/recherche-facettes
fix/import-doublons
docs/api-visibilite
refactor/annuaire-champs
```

### Rôle des branches

`main` :

- version stable ;
- destinée à la production ;
- aucune modification directe normale.

`develop` :

- branche d'intégration ;
- base du développement courant.

Les nouvelles branches partent normalement de `develop`.

---

## 4. Création d'une branche

Exemple :

```powershell
git switch develop
git pull --ff-only origin develop
git switch -c feature/profils-affiliations
```

Avant de commencer, vérifier :

```powershell
git status --short
git log -1 --oneline
git branch --show-current
```

Le working tree doit être propre.

---

## 5. Commits

Le projet utilise Conventional Commits avec description en français.

Format :

```text
<type>(<périmètre>): <description en français>
```

Types principaux :

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

### Règles

Un commit doit :

- avoir un objectif clair ;
- rester cohérent ;
- éviter les changements hors périmètre ;
- ne contenir aucun secret ;
- inclure les tests nécessaires lorsque pertinent.

À éviter :

```text
update
fix stuff
changes
wip
test
```

---

## 6. Architecture

Avant d'ajouter une nouvelle table ou un nouveau domaine, vérifier si le besoin peut être représenté par :

```text
Directory
ListingType
FieldDefinition
FieldGroup
Repeater
Taxonomy
Relationship
Location
View
Search Schema
```

Ne pas créer de vertical spécifique si le moteur générique suffit.

---

## 7. Séparations conceptuelles

Respecter impérativement :

```text
User != PersonProfile
Organization != OrganizationProfile
```

Un compte utilisateur n'est pas une fiche personne.

Un tenant SaaS n'est pas une fiche publique d'entreprise.

---

## 8. Marketplace

La marketplace transactionnelle est hors périmètre V1.

Ne pas créer sans validation explicite :

- panier ;
- commandes ;
- stock ;
- wallet ;
- commissions ;
- payouts ;
- livraison.

---

## 9. Sécurité

Ne jamais committer :

- `.env` ;
- mot de passe ;
- token ;
- secret OAuth ;
- clé API ;
- clé privée ;
- données KYC ;
- données personnelles confidentielles ;
- fichiers clients sensibles.

Avant un commit important :

```powershell
git diff --check
git diff --stat
git status --short
git diff --cached --stat
```

---

## 10. Multi-tenant

Toute ressource liée à une organisation doit être protégée côté serveur.

Un simple :

```text
X-Organization-Id
```

ne suffit pas.

Les contrôles doivent être appliqués via :

- middleware ;
- Policies ;
- services ;
- requêtes tenant-scopées ;
- tests.

Chaque domaine tenanté doit comporter des tests d'isolation.

---

## 11. Données personnelles

Les coordonnées de `PersonProfile` sont privées par défaut.

Une donnée privée ne doit pas être :

- renvoyée par une API publique ;
- indexée dans le moteur de recherche ;
- incluse dans un export non autorisé ;
- exposée puis uniquement masquée côté frontend.

---

## 12. Migrations

Avant toute migration :

1. vérifier le modèle documenté ;
2. inspecter les migrations existantes ;
3. éviter les doublons ;
4. prévoir les foreign keys ;
5. prévoir les indexes ;
6. vérifier les contraintes ;
7. analyser l'impact en production.

Ne jamais créer une migration exploratoire sans validation de l'architecture.

---

## 13. Tests

Toute évolution fonctionnelle ou technique significative doit comporter les tests appropriés.

Types :

```text
Unit
Feature
Integration
Regression
```

Cas à tester selon besoin :

- succès ;
- validation ;
- permissions ;
- isolation tenant ;
- entitlements ;
- quotas ;
- données privées ;
- doublons ;
- idempotence ;
- erreurs ;
- régression.

---

## 14. API

Les réponses API utilisent des Resources dédiées.

Les données privées ne sont jamais simplement filtrées côté interface.

Une rupture incompatible ne doit pas être introduite silencieusement.

Base actuelle :

```text
/api/v1
```

---

## 15. Recherche

PostgreSQL est la source de vérité.

Le moteur de recherche est un index dérivé.

Ne jamais stocker une donnée métier uniquement dans Typesense, Meilisearch ou un autre moteur de recherche.

Le choix Typesense / Meilisearch doit être validé séparément.

---

## 16. DataExchange

Les imports doivent respecter :

```text
Upload
→ Staging
→ Normalisation
→ Validation
→ Mapping
→ Dédoublonnage
→ Dry-run
→ Exécution
→ Indexation
→ Rapport
```

Un import ne doit jamais contourner :

- Policies ;
- règles métier ;
- Location Core ;
- taxonomies contrôlées ;
- services domaine.

---

## 17. External Profiles

Les providers externes sont isolés.

Chaque intégration doit gérer :

- OAuth ;
- scopes ;
- erreurs ;
- rate limits ;
- révocation ;
- audit ;
- sécurité des tokens.

Une limitation de Google, LinkedIn, Facebook, YouTube ou TikTok ne doit pas imposer sa structure au Core Jaxaay.

---

## 18. Documentation

Une évolution importante peut nécessiter la mise à jour de :

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

Ne pas modifier un document sans raison.

---

## 19. CHANGELOG

Le changelog décrit uniquement les changements réellement intégrés.

Exemple correct :

```text
Added
- Ajout de la gestion des affiliations professionnelles.
```

uniquement lorsque la fonctionnalité existe réellement dans le dépôt.

Une fonction future reste dans `ROADMAP.md`.

---

## 20. ADR

Créer ou mettre à jour un ADR lorsqu'une décision :

- modifie l'architecture ;
- modifie le modèle de données ;
- introduit une technologie majeure ;
- est difficile à inverser ;
- affecte plusieurs domaines.

Les ADR sont rédigés en français.

---

## 21. Pull Requests

Toute Pull Request doit être rédigée en français.

Elle doit décrire :

### Contexte

Pourquoi cette modification est nécessaire.

### Objectif

Ce que la PR doit accomplir.

### Changements

Liste claire des modifications.

### Architecture

Documents et ADR concernés.

### Tests

Commandes exécutées et résultats.

### Sécurité

Impacts :

- tenant ;
- données privées ;
- permissions ;
- uploads ;
- secrets ;
- intégrations.

### Documentation

Documents modifiés.

### Risques / limites

Dette technique, limitation ou décision reportée.

---

## 22. Checklist de Pull Request

```text
[ ] besoin compris
[ ] périmètre respecté
[ ] architecture respectée
[ ] tests ajoutés
[ ] tests existants verts
[ ] isolation tenant vérifiée
[ ] données privées protégées
[ ] migrations revues
[ ] indexes vérifiés
[ ] API cohérente
[ ] aucun secret
[ ] documentation mise à jour
[ ] CHANGELOG mis à jour si changement réellement intégré
[ ] ADR créé ou mis à jour si nécessaire
```

---

## 23. Revue de code

La revue doit vérifier :

- cohérence fonctionnelle ;
- simplicité ;
- sécurité ;
- isolation tenant ;
- performance ;
- duplication ;
- qualité des tests ;
- conformité à la documentation ;
- compatibilité API ;
- qualité des migrations.

Les commentaires de revue sont rédigés en français.

---

## 24. Dépendances

Avant d'ajouter une dépendance :

- vérifier sa maintenance ;
- vérifier sa licence ;
- vérifier sa compatibilité ;
- vérifier ses vulnérabilités connues ;
- vérifier qu'un composant Laravel/PHP/Flutter standard ne suffit pas.

Éviter les dépendances inutiles.

---

## 25. Codex

Lorsqu'une tâche est confiée à Codex :

- le périmètre doit être explicite ;
- la baseline Git doit être vérifiée ;
- les interdictions doivent être listées lorsque nécessaire ;
- les tests obligatoires doivent être indiqués ;
- le rapport final doit être rédigé en français.

Sauf demande explicite :

```text
Aucun stage
Aucun commit
Aucun push
```

---

## 26. Définition de Done

Une contribution importante est terminée lorsque :

```text
Code
+ Tests
+ Sécurité
+ Documentation
+ Validation
```

sont complets.

Le simple fait qu'une fonctionnalité fonctionne manuellement ne suffit pas.

---

## 27. Principe final

Toute contribution doit préserver :

```text
simplicité
+ cohérence
+ sécurité
+ performance
+ testabilité
+ traçabilité
+ documentation
```

et éviter :

```text
changements hors périmètre
+ logique métier dispersée
+ duplication
+ données privées exposées
+ commits vagues
+ dépendances inutiles
+ architecture non documentée
```
