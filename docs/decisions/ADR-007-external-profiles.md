# ADR-007 — Isoler les intégrations externes dans ExternalProfiles

- **Statut :** Accepté
- **Date :** 2026-09-05

## Contexte
Jaxaay prévoit des intégrations avec Google Business Profile, LinkedIn, Facebook, YouTube et TikTok. Chaque plateforme possède ses propres API, permissions et restrictions.

## Décision
Créer un domaine `ExternalProfiles` avec connecteurs indépendants :

```text
ExternalProfileProvider
├── GoogleBusinessConnector
├── LinkedInConnector
├── FacebookConnector
├── YouTubeConnector
└── TikTokConnector
```

## Fonctions possibles
Selon les capacités de chaque API :
- recherche anti-doublon ;
- connexion d’un profil existant ;
- import ;
- synchronisation ;
- push de modifications ;
- suivi des statuts ;
- audit des synchronisations.

## Conséquences
### Positives
- Faible couplage.
- Une restriction d’un fournisseur n’impacte pas le Core.
- Possibilité d’ajouter d’autres plateformes.

### Négatives
- Maintenance de plusieurs connecteurs.
- Dépendance aux politiques des fournisseurs.
