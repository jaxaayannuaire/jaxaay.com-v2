# Jaxaay Annuaire V2 — Recherche, facettes et ranking

**Version : 0.1**  
**Statut : Spécification fonctionnelle V1**

## 1. Objectif

La recherche doit être rapide, multi-entités, géographique, facettée et capable de classer les résultats selon l'intention.

## 2. Exemple

```text
ophtalmologue kaolack
```

Ordre cible :

1. profils ophtalmologues à Kaolack ;
2. établissements correspondants ;
3. entreprises / organisations ;
4. annonces pertinentes ;
5. documents ;
6. localités ou contenus associés.

## 3. Entités indexées

- PersonProfile ;
- OrganizationProfile ;
- Announcement ;
- Document ;
- Location.

## 4. Facettes

- annuaire ;
- type ;
- catégorie ;
- profession ;
- spécialité ;
- localisation ;
- rayon ;
- vérifié ;
- featured ;
- note ;
- champs dynamiques déclarés filtrables.

## 5. UX

```text
Filtre modifié
→ requête asynchrone
→ résultats actualisés
→ facettes actualisées
→ compteurs actualisés
→ carte actualisée
→ URL actualisée
```

Pas de rechargement complet.

## 6. URL

Les états de recherche importants doivent être :

- partageables ;
- compatibles historique navigateur ;
- indexables seulement lorsque prévu par la stratégie SEO.

## 7. Ranking

Signaux possibles :

- pertinence textuelle ;
- poids du type d'entité ;
- localisation ;
- vérification ;
- qualité ;
- popularité ;
- fraîcheur ;
- featured.

Le sponsorisé ne doit pas rendre l'ordre organique incohérent.

## 8. Autocomplete

Suggestions :

- personnes ;
- entreprises ;
- professions ;
- catégories ;
- localités.

## 9. Moteur

PostgreSQL reste la source de vérité.

La recherche externe est dérivée via Laravel Scout et un moteur dédié à sélectionner entre Typesense et Meilisearch.

## 10. Résilience

Une panne du moteur de recherche ne doit jamais provoquer une perte de données métier.

Une réindexation complète doit être possible.
