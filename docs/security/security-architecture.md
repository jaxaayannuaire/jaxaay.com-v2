# Jaxaay Annuaire V2 — Architecture de sécurité

**Version : 0.1**  
**Statut : Spécification de sécurité V1**  
**Projet : Jaxaay Annuaire V2**

## 1. Objectif

Définir les règles de sécurité applicables au Core SaaS, au Directory Engine, aux profils, aux entreprises, aux annonces, aux documents, aux imports, aux intégrations externes et aux futurs clients Web / Flutter / Telegram.

Principes :

- sécurité par défaut ;
- moindre privilège ;
- isolation stricte des organisations ;
- confidentialité des données personnelles ;
- audit des opérations sensibles ;
- validation côté serveur ;
- séparation entre authentification, autorisation, entitlements et propriété des ressources.

---

## 2. Modèle de confiance

```text
Client Web / Flutter / Bot / Intégration
            ↓
        API Laravel
            ↓
 Authentication
            ↓
 Organization Context
            ↓
 Authorization / Policies
            ↓
 Entitlements / Quotas
            ↓
 Domain Services
            ↓
 PostgreSQL / Redis / Search
```

Aucun client n'accède directement à PostgreSQL.

---

## 3. Authentification

Technologie cible :

- Laravel Sanctum pour les clients applicatifs ;
- OAuth 2 pour les fournisseurs externes ;
- 2FA obligatoire pour les comptes administratifs sensibles.

Fonctions :

- login ;
- logout ;
- révocation de tokens ;
- gestion de session ;
- récupération de mot de passe ;
- vérification email ;
- historique de connexions ;
- détection d'activité inhabituelle à terme.

---

## 4. Identifiants exposés

Ne pas exposer les clés PostgreSQL séquentielles comme identifiants métier publics.

Utiliser :

```text
id        → interne
public_id → API
reference → identifiant métier humain
```

Exemples :

```text
PF2609-0001
AN2609-0001
DOC2609-0001
```

---

## 5. Isolation multi-tenant

Toute ressource liée à une organisation doit être filtrée et autorisée côté serveur.

Contrôles obligatoires :

- middleware ;
- Policies Laravel ;
- services métier ;
- validation des relations ;
- requêtes Eloquent ;
- tests automatisés.

Le header :

```text
X-Organization-Id
```

sert à résoudre le contexte mais ne constitue jamais une preuve d'autorisation.

---

## 6. Autorisation

La décision d'accès combine :

```text
User
+ Role / Permission
+ Organization membership
+ Resource ownership
+ Entitlement
+ Quota
+ Resource status
```

Exemple :

```text
Utilisateur connecté
+ membre de l'organisation
+ permission publish_announcement
+ module announcements actif
+ quota disponible
→ autorisé
```

---

## 7. Rôles

Rôles initiaux possibles :

- platform_admin ;
- platform_moderator ;
- organization_owner ;
- organization_admin ;
- organization_editor ;
- organization_member ;
- contributor ;
- user.

Les rôles ne doivent pas remplacer les Policies métier.

---

## 8. Données personnelles

Les données personnelles des `PersonProfile` sont privées par défaut.

Exemples :

- téléphone ;
- email ;
- WhatsApp ;
- adresse ;
- date de naissance complète ;
- documents d'identité ;
- preuves KYC.

Visibilités possibles :

```text
public
authenticated
organization
owner_only
private
```

Une donnée privée ne doit jamais être envoyée dans la réponse API puis simplement masquée côté frontend.

---

## 9. Profils sensibles

Les profils suivants nécessitent une validation humaine :

- responsables politiques ;
- élus ;
- personnalités publiques ;
- professions réglementées ;
- médecins ;
- avocats ;
- scientifiques / universitaires selon contexte ;
- responsables religieux ;
- profils institutionnels.

L'IA ne peut pas publier ou vérifier automatiquement ces profils.

---

## 10. Claim et KYC

`claimed` et `verified` sont distincts.

Le KYC peut contenir :

- CNI ;
- passeport ;
- NINEA ;
- RCCM ;
- justificatif professionnel ;
- justificatif d'organisation ;
- document institutionnel.

Règles :

- accès restreint ;
- chiffrement au repos lorsque nécessaire ;
- journalisation ;
- durée de conservation définie ;
- suppression contrôlée ;
- aucun document KYC dans le moteur de recherche.

---

## 11. Chiffrement

À chiffrer :

- secrets OAuth ;
- tokens externes ;
- clés API ;
- données KYC sensibles ;
- certains contacts privés si nécessaire.

Les secrets ne doivent jamais apparaître :

- dans Git ;
- dans les logs ;
- dans les erreurs HTTP ;
- dans les exports ;
- dans les traces frontend.

---

## 12. Gestion des secrets

Utiliser :

- variables d'environnement ;
- secrets CI/CD ;
- gestionnaire de secrets selon infrastructure future.

Ne jamais stocker de secret dans :

```text
README.md
ROADMAP.md
config committed
fixtures
tests publics
```

---

## 13. Validation serveur

Toute entrée utilisateur est validée côté Laravel.

À contrôler notamment :

- types ;
- longueurs ;
- formats ;
- enums ;
- relations ;
- permissions ;
- MIME types ;
- taille de fichiers ;
- URLs ;
- champs dynamiques ;
- repeaters.

La configuration d'un champ dynamique ne doit jamais permettre de contourner la validation serveur.

---

## 14. Uploads

Tous les fichiers doivent être contrôlés.

Mesures :

- whitelist MIME ;
- taille maximale ;
- renommage serveur ;
- stockage hors webroot lorsque pertinent ;
- checksum ;
- antivirus / scan futur ;
- images réencodées si nécessaire ;
- suppression des métadonnées sensibles si politique retenue ;
- génération de variantes optimisées.

Interdire l'exécution de fichiers uploadés.

---

## 15. Documents / Archives

Mesures spécifiques :

- contrôle MIME ;
- taille ;
- visibilité ;
- provenance ;
- droits de téléchargement ;
- journalisation si document privé ;
- prévention des accès directs non autorisés.

---

## 16. Rate limiting

Profils de limite distincts :

- anonymous ;
- authenticated ;
- organization ;
- bot ;
- integration ;
- admin.

Endpoints sensibles :

- login ;
- password reset ;
- search ;
- leads ;
- claims ;
- reviews ;
- reports ;
- imports ;
- external sync ;
- webhooks.

---

## 17. Protection anti-abus

Prévoir :

- Cloudflare Turnstile ou équivalent ;
- rate limits ;
- limites de publication ;
- modération ;
- signalements ;
- listes de blocage ;
- détection de spam ;
- seuils de confiance ;
- limitation de création de comptes.

---

## 18. Reviews et modération

Les signalements peuvent déclencher :

```text
published
→ pending_review
```

mais pas une suppression automatique définitive par défaut.

Les opérations du modérateur sont auditées.

---

## 19. DataExchange

Les imports sont considérés comme opérations sensibles.

Règles :

- dry-run ;
- validation ;
- mapping explicite ;
- détection des doublons ;
- contrôles de permission ;
- traitement par queue ;
- journal d'import ;
- erreurs par ligne ;
- idempotence ;
- rollback lorsque possible.

Un import ne contourne jamais :

- Policies ;
- règles métier ;
- taxonomies contrôlées ;
- Location Core ;
- validation des références.

---

## 20. Directory Blueprints

Les Blueprints JSON ne doivent contenir que la configuration.

Ils ne doivent pas inclure :

- secrets ;
- données KYC ;
- tokens OAuth ;
- données personnelles privées ;
- mots de passe ;
- clés API.

---

## 21. Location Core

Les clients ne peuvent pas modifier directement les localités structurantes.

Flux :

```text
Suggestion
→ Review
→ Approval / Merge / Reject
```

Les fusions sont auditées.

---

## 22. Dédoublonnage

Les opérations de merge sont sensibles.

Règles :

- suggestion automatique possible ;
- validation humaine ;
- journalisation ;
- conservation de l'ancienne référence ;
- possibilité de retraçage ;
- aucune fusion critique automatique par IA.

---

## 23. Search Engine

Le moteur de recherche ne doit indexer que les données autorisées.

Ne jamais indexer :

- téléphone privé ;
- email privé ;
- adresse privée ;
- KYC ;
- notes internes ;
- tokens ;
- données admin.

La visibilité doit être appliquée avant indexation.

---

## 24. External Profiles

Chaque connecteur :

- utilise OAuth lorsque possible ;
- possède ses scopes minimaux ;
- stocke les tokens chiffrés ;
- journalise les opérations ;
- gère expiration et révocation ;
- respecte les limites du fournisseur.

Une panne d'un fournisseur ne bloque pas le Core Jaxaay.

---

## 25. Webhooks

Tout webhook doit vérifier :

- signature ;
- timestamp lorsque disponible ;
- idempotence ;
- replay ;
- provider ;
- payload attendu.

Le payload brut peut être conservé de manière contrôlée pour audit si nécessaire.

---

## 26. Idempotence

À appliquer notamment pour :

- webhooks ;
- paiements ;
- imports ;
- création via bot ;
- synchronisations externes ;
- opérations de queue sensibles.

Support possible :

```text
Idempotency-Key
```

---

## 27. Telegram Bot

Le bot est un client API séparé.

```text
Telegram
→ Jaxaay Bot
→ API
→ Domain Services
```

Le bot :

- ne possède aucun accès direct à PostgreSQL ;
- utilise des scopes dédiés ;
- est rate-limité ;
- journalise les publications ;
- crée de préférence des brouillons avant publication.

---

## 28. IA

L'IA peut proposer :

- catégorie ;
- profession ;
- doublon ;
- relation ;
- correction ;
- résumé ;
- classification.

Elle ne peut pas :

- valider KYC ;
- vérifier une identité ;
- publier un profil sensible ;
- supprimer définitivement ;
- fusionner automatiquement deux personnalités ;
- modifier sans contrôle un référentiel critique.

---

## 29. Audit logs

Événements prioritaires :

- login administratif ;
- changement de rôle ;
- claim ;
- KYC ;
- vérification ;
- profil sensible ;
- taxonomie ;
- localisation ;
- import ;
- merge ;
- modération ;
- External Profiles ;
- changement de visibilité ;
- suppression.

Un audit doit permettre de répondre :

```text
Qui ?
Quand ?
Quoi ?
Avant ?
Après ?
Pourquoi ?
```

---

## 30. Journalisation

Ne jamais loguer en clair :

- mot de passe ;
- token ;
- secret OAuth ;
- document d'identité ;
- téléphone privé complet si évitable ;
- données bancaires futures.

Les logs doivent être structurés et corrélables.

---

## 31. Soft delete

Utiliser le soft delete pour les entités sensibles lorsque pertinent :

- profils ;
- entreprises ;
- annonces ;
- documents ;
- relations critiques.

La suppression physique est réservée aux cas prévus par politique de rétention ou obligation légale.

---

## 32. Sauvegardes

Prévoir :

- sauvegardes PostgreSQL ;
- sauvegardes médias ;
- chiffrement ;
- rotation ;
- restauration testée ;
- séparation production / sauvegarde.

Une sauvegarde non testée n'est pas considérée comme fiable.

---

## 33. Redis et queues

Les jobs doivent être :

- idempotents ;
- retryables ;
- observables ;
- limités en tentatives ;
- envoyés en dead-letter / failed jobs si nécessaire.

Les erreurs de queue ne doivent pas entraîner de corruption métier.

---

## 34. Sécurité PostgreSQL

Principes :

- utilisateur applicatif dédié ;
- permissions minimales ;
- aucune connexion publique directe ;
- SSL selon infrastructure ;
- contraintes ;
- foreign keys ;
- indexes ;
- backups ;
- monitoring.

---

## 35. CI/CD

Pipeline cible :

```text
Lint
→ Static analysis
→ Unit tests
→ Feature tests
→ Security checks
→ Build
→ Deploy staging
→ Smoke tests
→ Production
```

Les secrets CI/CD sont isolés du dépôt.

---

## 36. Dépendances

Avant ajout d'une dépendance :

- vérifier maintenance ;
- licence ;
- compatibilité Laravel ;
- vulnérabilités connues ;
- activité du projet ;
- nécessité réelle.

Éviter d'introduire un package lorsqu'une fonctionnalité Laravel standard suffit.

---

## 37. Tests sécurité

Cas obligatoires :

- accès cross-tenant ;
- accès à une fiche privée ;
- exposition de contacts privés ;
- modification sans permission ;
- claim frauduleux ;
- import non autorisé ;
- upload malveillant ;
- duplication ;
- webhook répété ;
- token révoqué ;
- External Profile appartenant à une autre organisation.

---

## 38. Environnements

Séparer :

```text
local
testing
staging
production
```

Les données KYC et secrets de production ne doivent jamais être copiés tels quels vers un environnement de développement.

---

## 39. Administration

Les comptes administratifs doivent bénéficier de contrôles renforcés :

- 2FA ;
- sessions limitées ;
- audit ;
- permissions fines ;
- pas de compte partagé ;
- réauthentification pour opérations critiques à terme.

---

## 40. Politique de divulgation

Avant ouverture publique du projet, prévoir :

- canal de signalement de vulnérabilité ;
- politique de sécurité ;
- `SECURITY.md` ;
- versions supportées ;
- procédure de correction.

---

## 41. Priorités avant production

### P0
- isolation tenant ;
- contacts privés ;
- Policies ;
- validation ;
- uploads ;
- audit ;
- secrets ;
- KYC.

### P1
- rate limiting ;
- anti-spam ;
- 2FA admin ;
- webhooks ;
- sécurité imports ;
- synchronisation externe.

### P2
- détection avancée d'abus ;
- alertes sécurité ;
- analyse comportementale ;
- automatisation renforcée.

---

## 42. Principe final

La sécurité doit être appliquée au niveau domaine et API.

```text
Security != Frontend hiding
```

Une information qui ne doit pas être accessible ne doit jamais être retournée au client, indexée dans le moteur de recherche ou présente dans un export non autorisé.
