# Jaxaay Annuaire V2 — External Profiles

**Version : 0.1**  
**Statut : Spécification fonctionnelle V1 / progressive**

## 1. Objectif

Relier une fiche Jaxaay à des profils externes.

Providers envisagés :

- Google Business Profile ;
- LinkedIn ;
- Facebook ;
- YouTube ;
- TikTok.

## 2. Principe

Chaque fournisseur est isolé dans son propre connecteur.

```text
ExternalProfileProvider
├── GoogleBusinessConnector
├── LinkedInConnector
├── FacebookConnector
├── YouTubeConnector
└── TikTokConnector
```

## 3. Fonctions générales

Selon l'API disponible :

- rechercher un profil existant ;
- connecter un profil ;
- importer certaines données ;
- synchroniser ;
- pousser des modifications ;
- lire le statut ;
- journaliser les synchronisations.

## 4. Google Business Profile

Objectifs prioritaires :

- recherche anti-doublon ;
- connexion d'une fiche existante ;
- lecture des données autorisées ;
- synchronisation ;
- modification des données autorisées ;
- suivi de vérification ;
- suppression lorsque l'API et les droits le permettent.

La validation finale appartient à Google.

## 5. LinkedIn et réseaux sociaux

Les fonctionnalités dépendent des permissions et programmes API.

Jaxaay ne doit jamais supposer qu'un cycle complet création/édition/suppression est disponible.

## 6. Synchronisation

Directions possibles :

```text
pull
push
bidirectional
```

uniquement lorsque le fournisseur l'autorise.

## 7. OAuth

Les connexions doivent utiliser OAuth et des scopes minimaux.

Les tokens sont chiffrés.

## 8. Audit

Chaque opération conserve :

- provider ;
- action ;
- sens ;
- statut ;
- date ;
- erreur éventuelle.

## 9. Dédoublonnage

Un identifiant externe peut renforcer la détection de doublons Jaxaay.
