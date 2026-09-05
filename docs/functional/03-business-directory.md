# Jaxaay Annuaire V2 — Entreprises / Organisations

**Version : 0.1**  
**Statut : Spécification fonctionnelle V1**

## 1. Objectif

Référencer de manière structurée :

- entreprises ;
- établissements ;
- commerces ;
- cabinets ;
- associations ;
- ONG ;
- institutions ;
- administrations ;
- structures professionnelles ;
- organisations diverses.

## 2. Principe

Une fiche publique d'entreprise peut exister avant la création d'un compte client.

```text
OrganizationProfile
     ↓
unclaimed
     ↓
Claim
     ↓
Organization SaaS
```

`OrganizationProfile` est la représentation publique.  
`Organization` est le tenant SaaS qui gère éventuellement cette fiche.

## 3. Informations principales

- raison sociale ;
- nom commercial ;
- sigle ;
- description ;
- type d'organisation ;
- catégories ;
- activités ;
- services ;
- NINEA ;
- RCCM ;
- date de création ;
- statut ;
- logo ;
- galerie ;
- horaires ;
- contacts ;
- site web ;
- réseaux sociaux ;
- localisations ;
- zones de service ;
- personnes liées ;
- documents liés.

## 4. Établissements multiples

Une organisation peut gérer plusieurs implantations.

```text
Entreprise
├── Siège
├── Agence Dakar
├── Agence Kaolack
└── Point de service
```

Chaque implantation peut avoir :

- localisation ;
- horaires ;
- téléphone ;
- email ;
- services disponibles ;
- responsable ;
- visibilité.

## 5. Relations avec les profils

Exemples :

- fondateur ;
- directeur ;
- employé ;
- professionnel ;
- responsable ;
- membre ;
- représentant.

Les relations sont datables et vérifiables.

## 6. Claim

Une entreprise peut être créée :

- par Jaxaay ;
- par import ;
- par un partenaire ;
- depuis une source externe ;
- par un futur client.

Le claim permet de relier une fiche existante à un tenant Jaxaay.

## 7. Vérification

Sources possibles :

- NINEA ;
- RCCM ;
- domaine professionnel ;
- justificatif ;
- institution ;
- Google Business Profile ;
- source officielle.

`claimed` et `verified` sont distincts.

## 8. Recherche

Filtres possibles :

- catégorie ;
- activité ;
- service ;
- localisation ;
- rayon ;
- vérifié ;
- ouvert maintenant ;
- note ;
- établissement ;
- attributs personnalisés.

## 9. Leads

La fiche peut recevoir :

- demande de contact ;
- demande de devis ;
- demande d'information ;
- demande de rendez-vous.

## 10. Données contrôlées

Les clients ne créent pas librement :

- catégories principales ;
- localités ;
- professions de référence ;
- types d'organisation structurants.

Ils peuvent proposer de nouvelles valeurs.

## 11. Référence métier

Un préfixe dédié sera défini pour les fiches d'entreprises/établissements avant implémentation définitive.
