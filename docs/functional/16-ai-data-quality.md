# Jaxaay Annuaire V2 — IA assistée et qualité des données

**Version : 0.1**  
**Statut : Spécification fonctionnelle progressive**

## 1. Objectif

Utiliser l'IA et des scripts pour améliorer la qualité des données sans leur confier les décisions sensibles.

## 2. Suggestions possibles

- catégorie ;
- profession ;
- spécialité ;
- tags ;
- localisation ;
- relation ;
- doublon ;
- correction orthographique ;
- résumé ;
- classification documentaire ;
- migration de contenu.

## 3. Exemple de doublon

```text
Clinique ABC
Clinique A.B.C.
CLINIQUE ABC KAOLACK
```

L'IA peut proposer une fusion mais ne l'exécute pas automatiquement.

## 4. Localités

L'agent peut signaler :

- doublon ;
- faute ;
- parent incorrect ;
- coordonnées incohérentes ;
- variante de nom.

## 5. Annonces

Si un client publie fréquemment du contenu mal classé, le système peut :

- détecter la tendance ;
- notifier le backend ;
- proposer une migration ;
- informer le client après décision.

## 6. Documents

L'IA peut extraire ou suggérer :

- titre ;
- résumé ;
- type ;
- mots-clés ;
- personnes ;
- organisations ;
- dates ;
- relations.

## 7. Restrictions

L'IA ne valide pas automatiquement :

- identité ;
- profession réglementée ;
- personnalité sensible ;
- KYC ;
- fusion critique ;
- suppression ;
- taxonomie structurante ;
- localité canonique.

## 8. Confiance

Chaque suggestion doit inclure :

- type ;
- valeur proposée ;
- score de confiance ;
- justification technique ;
- statut ;
- décision humaine éventuelle.

## 9. Audit

Les corrections acceptées doivent pouvoir être reliées à leur origine : humain, script ou agent IA.
