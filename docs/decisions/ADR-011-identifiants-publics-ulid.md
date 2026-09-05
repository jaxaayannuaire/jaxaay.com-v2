# ADR-011 — Identifiants publics ULID

## Contexte

Les clés séquentielles internes ne doivent pas être exposées par l'API SaaS.

## Décision

Les organisations conservent un `id` bigint interne PostgreSQL et reçoivent un
`public_id` ULID de 26 caractères, unique et immuable. L'API et le header
`X-Organization-Id` utilisent exclusivement ce `public_id`. Les références
métier humaines restent distinctes et seront introduites par le domaine concerné.

## Conséquences

L'ULID limite la prédictibilité et offre un tri temporel raisonnable, sans
remplacer les identifiants internes ni les futures références métier.

## Statut

Accepté pour Core SaaS 1A.
