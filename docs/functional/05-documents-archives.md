# Jaxaay Annuaire V2 — Documents / Archives

**Version : 0.1**  
**Statut : Spécification fonctionnelle V1**

## 1. Objectif

Référencer des ressources documentaires structurées :

- décrets ;
- arrêtés ;
- rapports ;
- projets ;
- documents administratifs ;
- publications institutionnelles ;
- archives historiques ;
- ouvrages ;
- documents techniques.

## 2. Particularité

Ce sous-annuaire est plus simple que les annuaires géographiques.

La carte et la géolocalisation ne sont pas obligatoires.

## 3. Champs principaux

- titre ;
- type de document ;
- numéro ;
- date ;
- organisme émetteur ;
- résumé ;
- description ;
- langue ;
- nombre de pages ;
- fichier principal ;
- vignette ;
- statut ;
- source ;
- visibilité ;
- mots-clés ;
- relations.

## 4. Référence

Format :

```text
DOC + AAMM + séquence
```

Exemple :

```text
DOC2609-0001
```

## 5. Relations

Un document peut être relié à :

- PersonProfile ;
- OrganizationProfile ;
- Location ;
- autre Document ;
- Announcement si pertinent.

Exemples :

```text
Décret → Personnalité
Rapport → Institution
Projet → Localité
Décision → Organisation
```

## 6. Sources et provenance

Chaque document doit pouvoir conserver :

- source ;
- organisme ;
- URL d'origine ;
- date de récupération ;
- caractère officiel ;
- statut de vérification.

## 7. Fichiers

Le moteur doit prévoir :

- PDF ;
- documents bureautiques ;
- images ;
- autres formats autorisés.

Les fichiers sont contrôlés et stockés via la couche média commune.

## 8. Recherche

Filtres :

- nature ;
- date ;
- organisme ;
- personne liée ;
- organisation liée ;
- localité ;
- langue ;
- statut vérifié.

## 9. IA assistée

L'IA peut proposer :

- type documentaire ;
- résumé ;
- mots-clés ;
- personnes citées ;
- organisations citées ;
- localité ;
- relations probables.

La validation du classement final reste contrôlée selon la sensibilité du contenu.
