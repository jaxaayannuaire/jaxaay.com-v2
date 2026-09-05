# Jaxaay Annuaire V2 — Sous-annuaire Profils

**Version : 0.1**  
**Statut : Spécification fonctionnelle V1**

## 1. Objectif

Le sous-annuaire Profils référence des personnes physiques présentant un intérêt professionnel, scientifique, institutionnel, religieux, politique ou public.

Principe :

```text
User != PersonProfile
```

Une personne peut être référencée sans posséder de compte Jaxaay.

## 2. Types de profils

Types configurables :

- Professionnel ;
- Médecin / professionnel de santé ;
- Avocat / profession réglementée ;
- Scientifique ;
- Chercheur ;
- Professeur / universitaire ;
- Religieux ;
- Entrepreneur ;
- Personnalité publique ;
- Responsable politique ;
- Élu ;
- Artiste ;
- Expert ;
- autres types administrables.

## 3. Structure

```text
PersonProfile
├── Identité
├── Biographie
├── Professions[]
├── Spécialités[]
├── Compétences[]
├── Diplômes[]
├── Expériences[]
├── Mandats[]
├── Affiliations[]
├── Localisations[]
├── Contacts[]
├── Sources[]
├── Médias[]
├── Documents liés[]
└── Verification
```

## 4. Affiliations professionnelles

```text
PersonProfile
      ↓
ProfessionalAffiliation
      ↓
OrganizationProfile
```

Une affiliation peut contenir :

- fonction ;
- profession ;
- spécialité ;
- organisation ;
- date début ;
- date fin ;
- activité actuelle ;
- relation principale ;
- source ;
- statut de vérification.

## 5. Confidentialité

Les contacts personnels sont privés par défaut.

Visibilités :

```text
public
authenticated
organization
owner_only
private
```

Le propriétaire peut rendre volontairement certains contacts publics.

Un bouton de contact peut créer un Lead sans révéler téléphone ou email.

## 6. Publication et validation

```text
draft
→ pending_review
→ published
→ verified
→ archived / suspended
```

Les profils sensibles ne sont jamais publiés automatiquement.

```text
claimed != verified
```

## 7. Vérification

Types de vérification possibles :

- identité ;
- profession ;
- ordre professionnel ;
- mandat ;
- organisation ;
- institution ;
- source officielle ;
- document administratif ;
- plateforme externe.

## 8. Sources

Chaque information importante peut être reliée à une source :

- organisme ;
- URL ;
- document ;
- date ;
- caractère officiel ;
- statut de vérification.

## 9. Recherche

Pour une requête comme :

```text
ophtalmologue kaolack
```

ordre cible :

1. profils ophtalmologues à Kaolack ;
2. cabinets et établissements associés ;
3. entreprises pertinentes ;
4. annonces ;
5. documents ou contenus associés.

## 10. Professions et spécialités

Les professions sont des taxonomies contrôlées.

L'utilisateur peut proposer une valeur mais ne peut pas créer directement un terme structurant.

## 11. Documents liés

Un profil peut être relié à :

- décrets ;
- nominations ;
- publications ;
- rapports ;
- ouvrages ;
- décisions administratives ;
- archives.

## 12. Avis

Le moteur doit permettre une notation simple ou multi-critères selon le type de profil.

Les critères restent configurables.

## 13. Référence

Format initial :

```text
PF + AAMM + séquence
```

Exemple :

```text
PF2609-0001
```

## 14. Claim

Une fiche créée par Jaxaay peut être revendiquée par la personne concernée après contrôle d'identité et justificatifs.

## 15. IA assistée

L'IA peut suggérer :

- profession ;
- spécialité ;
- catégorie ;
- doublon ;
- organisation ;
- localisation ;
- relation ;
- résumé biographique.

Elle ne peut pas valider automatiquement une identité, une profession réglementée ou une personnalité sensible.
