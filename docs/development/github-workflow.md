# Jaxaay Annuaire V2 — Workflow GitHub

**Version : 0.1**

## 1. Branches

```text
main
develop
feature/*
fix/*
docs/*
refactor/*
```

## 2. Protection recommandée de `main`

À activer lorsque le dépôt sera initialisé :

- Pull Request obligatoire ;
- au moins 1 review ;
- branche à jour avant merge ;
- CI obligatoire ;
- conversation résolue ;
- force push interdit ;
- suppression de branche protégée interdite.

## 3. Protection de `develop`

Recommandé :

- PR obligatoire pour les évolutions majeures ;
- CI verte ;
- force push interdit.

## 4. Labels recommandés

### Type
- `bug`
- `enhancement`
- `documentation`
- `security`
- `refactor`
- `testing`

### Domaine
- `core-saas`
- `directory-engine`
- `profiles`
- `businesses`
- `announcements`
- `documents`
- `location`
- `search`
- `data-exchange`
- `claims`
- `moderation`
- `external-profiles`
- `flutter`
- `infrastructure`

### Priorité
- `P0`
- `P1`
- `P2`
- `P3`

### Statut
- `needs-spec`
- `ready`
- `in-progress`
- `blocked`
- `needs-review`

## 5. Milestones

Exemples :

- Architecture v1
- Core SaaS
- Directory Engine
- Search V1
- Jaxaay V2 MVP
- Beta
- Production V1

## 6. Pull Request

Workflow :

```text
Issue / Spec
→ Branch
→ Implementation
→ Tests
→ Documentation
→ PR
→ Review
→ CI
→ Merge develop
→ Validation
→ Release
→ main
```

## 7. Releases

Utiliser Semantic Versioning :

```text
0.x.y → pré-production
1.0.0 → première version stable
```

Tags :

```text
v0.1.0
v0.2.0
v1.0.0
```

## 8. Changelog

`CHANGELOG.md` est mis à jour pour les modifications réellement fusionnées.

## 9. Issues

Une issue importante doit référencer :

- domaine ;
- priorité ;
- milestone ;
- critères d'acceptation ;
- ADR / documentation concernée.

## 10. CI/CD

Ne pas ajouter un workflow CI fictif avant l'initialisation réelle de Laravel/Flutter.

Quand les applications existent, créer des workflows séparés :

```text
ci-api.yml
ci-web.yml
ci-mobile.yml
security.yml
```

## 11. Dependabot

À activer après présence des manifests réels :

- Composer ;
- npm ;
- pub ;
- GitHub Actions.

## 12. CODEOWNERS

Le fichier fourni est un exemple.

Valider les comptes ou équipes GitHub avant création du vrai `.github/CODEOWNERS`.
