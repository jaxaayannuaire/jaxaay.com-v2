# Jaxaay Annuaire V2 — Leads, favoris et alertes

**Version : 0.1**  
**Statut : Spécification fonctionnelle V1**

## 1. Objectif

Transformer l'annuaire en outil de mise en relation sans nécessiter une marketplace transactionnelle.

## 2. Leads

Types :

- contact ;
- demande de devis ;
- demande d'information ;
- demande de rendez-vous.

## 3. Confidentialité

Un Lead peut être transmis sans exposer les coordonnées privées du destinataire.

## 4. Demande de devis

Flux :

```text
Utilisateur
→ besoin
→ catégorie
→ localisation
→ entreprises pertinentes
→ demandes
→ réponses
```

Une demande peut être envoyée à une ou plusieurs entreprises selon règles.

## 5. Favoris

Un utilisateur peut enregistrer :

- profil ;
- entreprise ;
- annonce ;
- document.

## 6. Recherches sauvegardées

Exemples :

- emploi informatique Dakar ;
- appartement Kaolack ;
- ophtalmologue Thiès ;
- nouveaux documents administratifs.

## 7. Alertes

Canaux envisagés :

- email ;
- push Flutter ;
- Telegram.

## 8. Préférences

L'utilisateur peut choisir :

- type d'événement ;
- fréquence ;
- canal ;
- recherche sauvegardée ;
- annuaire.

## 9. Anti-spam

Le système limite :

- fréquence des leads ;
- contacts répétés ;
- envois massifs ;
- abus.

## 10. Analytics

Les propriétaires peuvent voir selon leur plan :

- impressions ;
- ouvertures de fiche ;
- leads ;
- favoris ;
- clics externes.
