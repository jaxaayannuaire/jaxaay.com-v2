# Jaxaay Annuaire V2 — Location Core

**Version : 0.1**  
**Statut : Spécification fonctionnelle V1**

## 1. Objectif

Fournir un référentiel géographique unique pour tous les annuaires afin d'éviter les doublons et incohérences.

## 2. Hiérarchie cible

```text
Country
→ Region
→ Department
→ Commune
→ District / Neighborhood
```

Des niveaux supplémentaires peuvent être ajoutés par configuration si un besoin réel l'exige.

## 3. Données

Une localité peut contenir :

- nom canonique ;
- slug ;
- code ;
- parent ;
- type ;
- latitude ;
- longitude ;
- variantes de nom ;
- source ;
- statut.

## 4. Règle

Aucun annuaire ne possède son propre référentiel de localités.

## 5. Création

Les clients ne créent pas directement une localité structurante.

Ils peuvent :

- rechercher ;
- sélectionner ;
- proposer une correction ;
- proposer une nouvelle localité.

La proposition passe par validation.

## 6. Dédoublonnage

Signaux :

- nom normalisé ;
- parent ;
- coordonnées ;
- variantes orthographiques ;
- codes ;
- sources externes.

## 7. IA assistée

Un script ou agent pourra suggérer :

- doublon ;
- faute ;
- mauvaise hiérarchie ;
- fusion ;
- coordonnées probables.

Aucune fusion critique n'est automatique.

## 8. Géorecherche

Le Location Core doit permettre :

- recherche par rayon ;
- distance ;
- proximité ;
- zones de service ;
- tri géographique ;
- carte ;
- clustering.

## 9. Localisations multiples

Une fiche peut avoir plusieurs localisations :

- siège ;
- agence ;
- lieu de travail ;
- zone de service ;
- événement ;
- résidence si explicitement publique.

## 10. Import

Les imports massifs doivent mapper leurs localités vers le référentiel existant avant création de nouvelles valeurs.
