# ADR-008 — Ajouter Documents / Archives comme sous-annuaire V1

- **Statut :** Accepté
- **Date :** 2026-09-05

## Contexte
Jaxaay doit également référencer des contenus documentaires : décrets, rapports, projets, documents administratifs, publications institutionnelles et archives.

## Décision
Créer un sous-annuaire `Documents / Archives`, plus simple que les annuaires géographiques.

Caractéristiques :
- géolocalisation facultative ;
- fichier joint ;
- date / numéro de document ;
- résumé ;
- langue ;
- nombre de pages ;
- relations vers `PersonProfile` ;
- relations vers `OrganizationProfile` ;
- relations vers d’autres documents ;
- classification assistée par IA avec validation humaine.

## Conséquences
### Positives
- Centralisation des ressources documentaires.
- Relations riches avec profils et organisations.
- Réutilisable pour d’autres projets.

### Négatives
- Gestion du stockage documentaire.
- Besoin de règles de droits et de visibilité.
