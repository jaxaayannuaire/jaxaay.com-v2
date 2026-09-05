# Jaxaay Annuaire V2 — Sous-annuaire Annonces

**Version : 0.1**  
**Statut : Spécification fonctionnelle V1**

## 1. Objectif

Les Annonces accueillent les contenus temporaires et fréquemment publiés.

Types initiaux :

- emploi ;
- immobilier ;
- automobile ;
- services ;
- opportunités ;
- appels d'offres ;
- événements ;
- autres verticales configurables.

## 2. Principe

Les annonces utilisent le Directory Engine générique et ses champs dynamiques.

Une nouvelle verticale ne doit pas imposer une table métier dédiée si les champs configurables suffisent.

## 3. Workflow

```text
draft
→ pending_review
→ published
→ expired / archived
```

Les règles de modération peuvent varier selon le type.

## 4. Référence

Format :

```text
AN + AAMM + séquence
```

Exemple :

```text
AN2609-0001
```

## 5. Job

Exemples de champs :

- poste ;
- entreprise ;
- contrat ;
- localisation ;
- salaire ;
- expérience ;
- compétences ;
- date limite ;
- niveau d'études ;
- modalités de candidature.

## 6. Immobilier

Exemples :

- type de bien ;
- transaction ;
- prix ;
- surface ;
- pièces ;
- chambres ;
- équipements ;
- localisation ;
- géopoint ;
- disponibilité.

## 7. Automobile

Exemples :

- marque ;
- modèle ;
- année ;
- kilométrage ;
- carburant ;
- transmission ;
- prix ;
- localisation.

## 8. Publication fréquente

Le système doit détecter les clients publiant fréquemment.

Il peut notifier l'administration lorsqu'un contenu :

- est mal classé ;
- semble être un doublon ;
- devrait être transformé en fiche permanente ;
- devrait être rattaché à une autre entité.

Les corrections et migrations restent contrôlées par le backend.

## 9. Expiration

Les annonces peuvent posséder :

- date de début ;
- date d'expiration ;
- renouvellement ;
- archivage automatique.

## 10. Alertes

Les utilisateurs peuvent sauvegarder une recherche et recevoir des alertes sur de nouvelles annonces correspondantes.

## 11. Featured

La mise en avant commerciale peut être prévue sans modifier le classement organique de manière incohérente.
