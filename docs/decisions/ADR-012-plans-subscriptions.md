# ADR-012 — Plans et souscriptions du Core SaaS

- **Statut :** Accepté
- **Date :** 2026-09-05

## Contexte

Le Core SaaS 1A fournit les utilisateurs, organisations, memberships et le
contexte tenant. Il faut maintenant représenter le catalogue global d'offres
et la souscription d'une organisation, sans introduire les modules,
entitlements, quotas ou paiements.

## Décision

- `Plan` est un élément global du catalogue SaaS et est identifié publiquement
  par son `slug`.
- `Subscription` appartient à une `Organization` et expose un `public_id`
  ULID ; l'ID interne n'est jamais exposé par l'API.
- La souscription conserve un snapshot de `price`, `currency` et
  `billing_cycle`.
- Les seuls cycles sont `monthly` et `yearly` ; un prix annuel nul rend ce
  cycle indisponible.
- Une seule souscription courante non terminale (`pending`, `trialing`,
  `active`, `grace`) est autorisée par organisation. Les statuts `cancelled`
  et `expired` sont terminaux et peuvent rester dans l'historique.
- Une création API commence au statut `pending`; l'activation et le paiement
  sont différés.
- La création verrouille l'organisation dans une transaction et protège aussi
  l'invariant par un index PostgreSQL partiel.

## Conséquences

Les rôles `owner` et `admin` peuvent créer une souscription ; tout membre peut
consulter la souscription courante. Les modules, entitlements et quotas sont
explicitement différés au Core SaaS 1C.
