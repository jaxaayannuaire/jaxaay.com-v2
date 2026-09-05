# Jaxaay Annuaire V2 — DataExchange

**Version : 0.1**  
**Statut : Spécification fonctionnelle V1 prioritaire**

## 1. Objectif

Permettre :

- migration Jaxaay V1 → V2 ;
- imports massifs ;
- exports ;
- sauvegardes fonctionnelles de configuration ;
- migrations vers d'autres projets.

## 2. Formats

- CSV pour les contenus ;
- ZIP pour les médias ;
- JSON pour les Directory Blueprints.

## 3. Contenus importables/exportables

- fiches complètes ;
- champs personnalisés ;
- fieldsets / repeaters ;
- catégories ;
- tags ;
- professions ;
- localités ;
- relations ;
- références ;
- claims / données associées lorsque prévu.

## 4. Médias

Un ZIP peut contenir les images et fichiers référencés par le CSV.

Le mapping doit permettre d'associer chaque média à la bonne fiche.

## 5. Mapping

L'administrateur peut mapper :

```text
colonne CSV → champ Jaxaay
```

et enregistrer des mappings réutilisables.

## 6. Dry-run

Avant exécution :

- valider les colonnes ;
- détecter les erreurs ;
- vérifier les taxonomies ;
- vérifier les localités ;
- calculer les doublons ;
- afficher un rapport.

## 7. Pipeline

```text
Upload
→ Staging
→ Validation
→ Normalisation
→ Mapping
→ Dédoublonnage
→ Dry-run
→ Validation humaine
→ Queue
→ Import
→ Indexation
→ Rapport
```

## 8. Traitement par lots

Les gros imports sont asynchrones et reprenables.

Le suivi contient :

- total ;
- traité ;
- succès ;
- erreurs ;
- statut ;
- temps.

## 9. Dédoublonnage

Signaux :

- référence ;
- nom ;
- téléphone ;
- email ;
- NINEA ;
- RCCM ;
- URL ;
- localisation ;
- identifiants externes.

## 10. Mise à jour

Un import peut :

- créer ;
- mettre à jour ;
- ignorer ;
- proposer une fusion.

Une référence stable est privilégiée pour les mises à jour.

## 11. Rollback

Lorsque possible, un import doit pouvoir être annulé ou restauré à partir de son journal.

## 12. Directory Blueprints

Export/import de :

- Directory ;
- ListingTypes ;
- Fields ;
- Fieldsets ;
- Taxonomies ;
- Filters ;
- Views ;
- SEO ;
- Permissions.

Ce mécanisme doit permettre une future migration d'AGPS/WaPASTEF vers le moteur Jaxaay.
