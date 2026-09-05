# Jaxaay Annuaire V2 — Vue d'ensemble fonctionnelle

**Version : 0.1**  
**Statut : Spécification fonctionnelle V1**  
**Projet : Jaxaay Annuaire V2**

## 1. Objectif

Jaxaay Annuaire V2 est une plateforme SaaS multi-annuaire destinée à structurer, référencer, rechercher et mettre en relation :

- personnes physiques ;
- entreprises et établissements ;
- organisations et institutions ;
- annonces ;
- documents et archives ;
- localités ;
- contenus associés.

La priorité V1 est un moteur d'annuaire performant, léger, configurable et API-first. La marketplace transactionnelle est hors périmètre V1.

## 2. Sous-annuaires V1

```text
Jaxaay Annuaire
├── Profils
├── Entreprises / Organisations
├── Annonces
└── Documents / Archives
```

D'autres verticales doivent pouvoir être ajoutées principalement par configuration.

## 3. Principes fonctionnels

- `User` est distinct de `PersonProfile`.
- Une fiche publique peut exister sans compte propriétaire.
- Une entreprise peut être créée par Jaxaay avant d'être revendiquée.
- Les taxonomies et localités structurantes sont contrôlées.
- Tous les annuaires partagent un même Location Core.
- Les champs dynamiques, fieldsets et repeaters sont natifs.
- Les relations entre annuaires sont explicites.
- La recherche est multi-entités et facettée.
- Les profils sensibles nécessitent une validation humaine.
- Les données personnelles sont privées par défaut.
- Les imports massifs passent par validation, dédoublonnage et journalisation.

## 4. Domaines fonctionnels

1. Directory Engine.
2. Profils.
3. Entreprises / Organisations.
4. Annonces.
5. Documents / Archives.
6. Champs dynamiques / repeaters.
7. Taxonomies.
8. Location Core.
9. Recherche / facettes.
10. Claims / KYC / vérification.
11. Avis / modération.
12. DataExchange.
13. Leads / favoris / alertes.
14. SEO / vues / comparaison.
15. External Profiles.
16. Notifications / Telegram.
17. IA assistée / qualité des données.

## 5. Workflow général d'une fiche

```text
draft
→ pending_review
→ published
→ verified
→ archived / suspended
```

Selon le type de fiche, certaines étapes peuvent être facultatives, mais les profils sensibles et les vérifications institutionnelles ne sont jamais automatiques.

## 6. Identifiants métier

Exemples :

```text
PF2609-0001   Profil
AN2609-0001   Annonce
DOC2609-0001  Document
```

Une référence est unique, stable, immuable et jamais recyclée.

## 7. Références de conception

Jaxaay V2 s'inspire notamment de :

- Directories Pro pour le moteur d'annuaire configurable ;
- Expat-Dakar pour les annonces verticales ;
- Go Africa Online pour l'annuaire B2B et les leads ;
- Google Business Profile pour claim, vérification et géolocalisation ;
- Kompass pour la classification B2B ;
- Bulistio pour la monétisation future ;
- AGPS/WaPASTEF pour les relations inter-annuaires, niveaux de visibilité et modération.

## 8. Hors périmètre V1

- panier ;
- commandes ;
- stock ;
- commissions marketplace ;
- wallet ;
- payouts ;
- livraison ;
- marketplace transactionnelle complète.
