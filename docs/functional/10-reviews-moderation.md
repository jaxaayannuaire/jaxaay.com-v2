# Jaxaay Annuaire V2 — Avis, signalements et modération

**Version : 0.1**  
**Statut : Spécification fonctionnelle V1**

## 1. Objectif

Fournir une couche de confiance transversale à tous les annuaires.

## 2. Avis simple

- note globale ;
- commentaire ;
- média éventuel ;
- réponse du propriétaire selon type ;
- signalement.

## 3. Avis multi-critères

Les critères appartiennent au type de fiche.

Exemple professionnel :

- compétence ;
- qualité ;
- accueil ;
- réactivité.

Les critères ne doivent pas être codés en dur dans le moteur.

## 4. Unicité

Selon le ListingType, une règle peut limiter un utilisateur à un avis par fiche.

## 5. Signalements

Catégories possibles :

- contenu illégal ;
- menace ;
- haine ;
- harcèlement ;
- usurpation ;
- désinformation ;
- spam ;
- hors sujet ;
- doublon ;
- faible qualité.

## 6. Gravité

Chaque catégorie peut avoir un score.

Le moteur calcule :

- nombre de signalements ;
- gravité cumulée ;
- gravité moyenne.

## 7. Seuils

Un seuil peut automatiquement déplacer un contenu vers :

```text
pending_review
```

La décision finale reste humaine.

## 8. File de modération

Une file centralisée regroupe les signalements de tous les annuaires.

## 9. Audit

Chaque traitement conserve :

- modérateur ;
- décision ;
- date ;
- motif ;
- ancienne valeur ;
- nouvelle valeur.

## 10. Anti-abus

Prévoir :

- comptes connectés selon règles ;
- rate limiting ;
- CAPTCHA ;
- détection de spam ;
- suspension de compte si abus répétés.
