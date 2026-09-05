# ADR-001 — Réutiliser le modèle Core SaaS inspiré de Yessal ERP

- **Statut :** Accepté
- **Date :** 2026-09-05

## Contexte
Jaxaay Annuaire V2 doit fonctionner comme une plateforme SaaS multi-organisation avec abonnements, modules, entitlements et quotas, tout en restant cohérente avec l’écosystème Yessal ERP SaaS.

## Décision
Adopter comme socle :

```text
User
→ Organization
→ Subscription
→ Plan
→ Modules
→ Entitlements
→ Quotas
```

Le domaine Annuaire sera développé comme domaine métier au-dessus de ce Core.

## Alternatives considérées
- Repartir d’un script d’annuaire SaaS existant.
- Construire un système d’abonnement spécifique à Jaxaay.
- Utiliser directement Bulistio ou une autre solution monolithique.

## Conséquences
### Positives
- Cohérence avec Yessal ERP.
- Réutilisation de principes déjà éprouvés.
- Architecture extensible.
- Meilleure séparation entre SaaS et métier.

### Négatives
- Effort initial supérieur à un simple fork.
- Nécessite une documentation stricte des frontières entre Core et domaines métier.
