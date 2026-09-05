# Jaxaay Annuaire V2 — Notifications et Telegram

**Version : 0.1**  
**Statut : Spécification fonctionnelle V1 / intégration future**

## 1. Objectif

Découpler les événements métier des canaux de notification.

```text
Domain Event
→ Outbox
→ Notification Dispatcher
├── Email
├── Push Flutter
├── Telegram
└── futurs canaux
```

## 2. Événements

Exemples :

- claim reçu ;
- claim validé ;
- fiche rejetée ;
- profil vérifié ;
- nouveau lead ;
- annonce expirant bientôt ;
- nouvelle annonce correspondant à une alerte ;
- import terminé ;
- doublon probable détecté ;
- correction proposée.

## 3. Préférences utilisateur

L'utilisateur peut définir :

- canal ;
- fréquence ;
- type d'événement ;
- plages de notification si nécessaire.

## 4. Telegram Bot

Le bot est un projet séparé.

Architecture :

```text
Telegram
→ Bot
→ Jaxaay API
→ Application Services
→ PostgreSQL
```

Le bot ne se connecte jamais directement à la base.

## 5. Fonctions futures du bot

- alertes ;
- rappels ;
- notifications ;
- recherche ;
- consultation ;
- publication de brouillons d'annonces ;
- suivi validation ;
- informations sur l'abonnement.

## 6. Publication depuis Telegram

```text
Telegram
→ Bot
→ API
→ Announcement draft
→ Validation
→ Moderation
→ Published
```

## 7. Sécurité

Le bot utilise :

- token d'application ;
- scopes ;
- identité utilisateur liée ;
- rate limits ;
- audit.

## 8. Référence

Le bot Jaxaay pourra s'inspirer fonctionnellement de l'expérience JGH Alert Bot, mais son développement est géré séparément.
