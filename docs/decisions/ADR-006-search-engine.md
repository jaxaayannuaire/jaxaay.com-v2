# ADR-006 — Prévoir un moteur de recherche externe dès la V1

- **Statut :** Accepté
- **Date :** 2026-09-05

## Contexte
La recherche est une fonctionnalité centrale de Jaxaay.

Exemple attendu :

```text
ophtalmologue kaolack
```

doit pouvoir faire remonter d’abord les profils pertinents, puis établissements, entreprises, annonces, documents et localités.

## Décision
Utiliser PostgreSQL comme source de vérité et un moteur externe dérivé via Laravel Scout.

Candidats actuels :
- Typesense ;
- Meilisearch.

Le choix définitif fera l’objet d’un benchmark technique dédié.

Architecture :

```text
PostgreSQL
→ Domain Event
→ Outbox / Queue
→ Laravel Scout
→ Search Engine
```

## Exigences
- full-text ;
- facettes ;
- géorecherche ;
- autocomplete ;
- ranking multi-entités ;
- recherche AJAX/Livewire ;
- filtres et URL partageable.

## Conséquences
### Positives
- Recherche performante.
- UX moderne.
- Scalabilité.
- Ranking avancé.

### Négatives
- Infrastructure supplémentaire.
- Nécessité de synchroniser correctement l’index.
