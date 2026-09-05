# Jaxaay Annuaire V2 — Claims, vérification et KYC

**Version : 0.1**  
**Statut : Spécification fonctionnelle V1**

## 1. Objectif

Permettre à Jaxaay de créer des fiches avant leur propriétaire, puis de gérer la revendication et la vérification de manière distincte.

## 2. Claim

```text
Fiche non réclamée
→ Claim
→ Compte
→ Justificatifs
→ Review
→ Approved / Rejected
```

Un claim approuvé relie la fiche à un propriétaire ou une organisation.

## 3. Principe

```text
claimed != verified
```

Être propriétaire d'une fiche ne signifie pas que Jaxaay confirme toutes ses informations.

## 4. KYC

Documents possibles selon le contexte :

- CNI ;
- passeport ;
- NINEA ;
- RCCM ;
- preuve professionnelle ;
- justificatif d'organisation ;
- mandat ;
- document institutionnel.

## 5. Vérification

Types possibles :

- identity ;
- professional ;
- organization ;
- institutional ;
- official_source ;
- external_platform.

## 6. États

```text
unverified
pending
verified
rejected
expired
```

## 7. Profils sensibles

Les validations de :

- personnalités publiques ;
- responsables politiques ;
- professions réglementées ;
- élus ;
- scientifiques / universitaires selon contexte ;
- profils institutionnels ;

restent humaines.

## 8. Preuves

Chaque vérification doit pouvoir conserver :

- document ;
- source ;
- notes ;
- vérificateur ;
- date ;
- expiration éventuelle.

## 9. Audit

Toute décision sur claim ou vérification est journalisée.

## 10. External Profiles

Google Business Profile ou d'autres plateformes peuvent constituer un signal ou une source, mais pas remplacer automatiquement la décision Jaxaay.
