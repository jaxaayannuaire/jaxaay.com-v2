# Contribuer à Jaxaay Annuaire V2

## Branche de travail

```text
main
develop
feature/*
fix/*
docs/*
refactor/*
```

- `main` : stable / production.
- `develop` : intégration courante.
- Une évolution importante passe par une branche dédiée et une Pull Request.

## Avant de développer

Lire au minimum :

- `AGENTS.md`
- `README.md`
- `ROADMAP.md`
- `docs/architecture/architecture.md`
- `docs/architecture/database.md`
- `docs/architecture/api.md`
- `docs/security/security-architecture.md`

## Convention de commits

Style recommandé :

```text
feat(profile): add professional affiliations
feat(search): add location facets
fix(import): prevent duplicate references
docs(api): document profile visibility
test(tenant): cover cross-organization access
refactor(directory): extract field resolver
```

## Pull Requests

Une PR doit préciser :

- contexte ;
- objectif ;
- solution ;
- fichiers principaux ;
- migrations ;
- endpoints ajoutés/modifiés ;
- impacts sécurité ;
- tests exécutés ;
- documentation mise à jour ;
- risques / limites.

## Tests

Toute fonctionnalité métier majeure doit inclure les tests nécessaires :

- unit ;
- feature ;
- integration ;
- tenant isolation ;
- authorization ;
- regression.

## Documentation

Le `CHANGELOG.md` décrit uniquement les changements réellement intégrés.

Les fonctionnalités futures restent dans `ROADMAP.md`.

Une décision structurante doit être documentée dans `docs/decisions/`.
