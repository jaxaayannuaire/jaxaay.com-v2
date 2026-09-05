# Jaxaay Annuaire V2 — Architecture de déploiement

**Version : 0.1**  
**Statut : Architecture cible — pré-production**  
**Projet : Jaxaay Annuaire V2**

## 1. Objectif

Définir une architecture de déploiement simple au démarrage, mais capable d’évoluer avec la volumétrie.

Principes :

- séparation application / données / recherche ;
- déploiement reproductible ;
- haute observabilité ;
- sauvegardes testées ;
- montée en charge progressive ;
- aucune dépendance forte à un fournisseur unique.

## 2. Environnements

```text
local
testing
staging
production
```

Chaque environnement possède :

- variables propres ;
- base de données distincte ;
- clés et secrets distincts ;
- stockage distinct ;
- index de recherche distinct.

Les données sensibles de production ne sont pas copiées telles quelles vers les environnements inférieurs.

## 3. Architecture logique initiale

```text
Internet
   ↓
Reverse Proxy / TLS
   ↓
Laravel Web / API
   ├── PostgreSQL
   ├── Redis
   ├── Queue Workers
   ├── Scheduler
   ├── Search Engine
   └── Object / Media Storage
```

## 4. Web et API

Le backend Laravel 13 expose :

- API REST ;
- interface Web Laravel / Livewire ;
- endpoints d’administration ;
- webhooks ;
- endpoints d’intégration.

Le frontend Web peut être déployé avec le backend tant que la volumétrie reste raisonnable.

## 5. PostgreSQL

PostgreSQL est la source de vérité.

Exigences :

- sauvegardes automatiques ;
- restauration testée ;
- monitoring ;
- indexes adaptés ;
- accès réseau privé ;
- utilisateur applicatif dédié ;
- aucune exposition publique directe.

## 6. Redis

Redis est utilisé pour :

- cache ;
- queues ;
- rate limiting ;
- locks ;
- données temporaires.

Redis ne constitue jamais une source métier définitive.

## 7. Queue Workers

Workers séparés pour les traitements lourds :

```text
default
imports
search
notifications
external-sync
media
ai
```

Cette séparation pourra être progressive.

## 8. Scheduler

Laravel Scheduler orchestre notamment :

- expirations ;
- alertes ;
- nettoyage ;
- réindexation ciblée ;
- synchronisations ;
- renouvellements ;
- maintenance.

Le scheduler doit être exécuté de manière fiable par l’infrastructure.

## 9. Search Engine

Le moteur de recherche est déployé comme service indépendant.

Candidats :

- Typesense ;
- Meilisearch.

Architecture :

```text
PostgreSQL
→ Outbox
→ Queue
→ Laravel Scout
→ Search Engine
```

Une réindexation complète doit être possible à tout moment.

## 10. Médias

Les médias doivent être séparés du code applicatif.

Prévoir :

- stockage objet ou volume persistant ;
- génération de variantes ;
- CDN ultérieur ;
- sauvegardes ;
- contrôle d’accès pour fichiers privés.

## 11. Reverse Proxy et TLS

Le point d’entrée doit gérer :

- HTTPS ;
- redirections ;
- compression ;
- limites de taille ;
- headers de sécurité ;
- rate limiting complémentaire ;
- proxy vers Laravel.

## 12. Domaines

Structure indicative :

```text
www.jaxaay.com
api.jaxaay.com
admin.jaxaay.com
```

Les noms définitifs seront validés lors du déploiement réel.

## 13. Containers

Docker est recommandé pour rendre les environnements reproductibles.

Services possibles :

```text
app
queue
scheduler
postgres
redis
search
proxy
```

La configuration Docker définitive doit rester simple et documentée.

## 14. CI/CD

Pipeline cible :

```text
Push / PR
→ Lint
→ Static Analysis
→ Tests
→ Security Checks
→ Build
→ Staging
→ Smoke Tests
→ Production
```

Aucun déploiement production ne doit dépendre d’une modification manuelle non documentée.

## 15. Branches

```text
main
develop
feature/*
fix/*
docs/*
```

- `main` : stable / production ;
- `develop` : intégration ;
- feature branches : développement isolé.

## 16. Migrations production

Les migrations doivent :

- être revues ;
- être testées sur staging ;
- avoir un impact estimé ;
- éviter les blocages longs ;
- disposer d’un plan de retour lorsque nécessaire.

## 17. Secrets

Les secrets sont injectés via l’environnement ou le système CI/CD.

Ne jamais committer :

- mots de passe ;
- clés API ;
- tokens OAuth ;
- secrets Wave futurs ;
- credentials PostgreSQL ;
- credentials providers externes.

## 18. Sauvegardes

Sauvegarder :

- PostgreSQL ;
- médias ;
- configurations critiques ;
- secrets via mécanisme dédié ;
- Directory Blueprints importants.

Politique minimale :

```text
quotidien
+ rotation
+ copie externe
+ test périodique de restauration
```

## 19. Monitoring

Surveiller :

- disponibilité HTTP ;
- erreurs Laravel ;
- CPU ;
- RAM ;
- disque ;
- PostgreSQL ;
- Redis ;
- queues ;
- failed jobs ;
- latence recherche ;
- stockage médias ;
- certificats TLS.

## 20. Logs

Les logs applicatifs doivent être centralisables.

Inclure :

- request_id ;
- user_id si pertinent ;
- organization_id ;
- job_id ;
- provider ;
- entity_id.

Les secrets et données KYC ne sont jamais journalisés en clair.

## 21. Alerting

Alertes prioritaires :

- site indisponible ;
- base indisponible ;
- disque faible ;
- workers arrêtés ;
- accumulation de queues ;
- failed jobs ;
- sauvegarde échouée ;
- certificat expirant ;
- search engine indisponible.

Le futur Jaxaay Bot pourra recevoir certaines alertes d’exploitation, mais il restera client d’API ou de services dédiés.

## 22. Scalabilité

Phase initiale :

```text
1 application
1 PostgreSQL
1 Redis
1 Search
N workers
```

Évolution :

```text
Load Balancer
├── App 1
├── App 2
└── App N

PostgreSQL dédié
Redis dédié
Search cluster / instance dédiée
Object Storage / CDN
Workers dédiés par queue
```

## 23. Sessions

Pour permettre plusieurs instances applicatives :

- sessions côté Redis ou token-based ;
- aucun état critique uniquement sur disque local.

## 24. Haute disponibilité

La haute disponibilité complète n’est pas obligatoire au lancement.

Priorité :

1. sauvegardes fiables ;
2. restauration rapide ;
3. monitoring ;
4. automatisation ;
5. duplication progressive des composants critiques.

## 25. Déploiement SaaS

Le modèle cible est une plateforme partagée multi-tenant, et non une instance par client.

```text
Jaxaay Platform
├── Organization A
├── Organization B
├── Organization C
└── ...
```

Les données sont isolées logiquement et protégées par les règles multi-tenant.

## 26. Staging

Staging doit reproduire autant que possible :

- versions PHP ;
- PostgreSQL ;
- Redis ;
- Search ;
- queue ;
- configuration proxy.

Les tests d’intégration externes y sont effectués avant production.

## 27. External Profiles

Les connecteurs externes utilisent des credentials spécifiques à l’environnement.

Ne jamais mélanger :

```text
staging OAuth
production OAuth
```

Les webhooks doivent pointer vers l’environnement correspondant.

## 28. DataExchange

Les imports volumineux utilisent :

- stockage temporaire ;
- queue dédiée ;
- limites de taille ;
- logs ;
- nettoyage automatique.

Les fichiers temporaires sont supprimés après la politique de rétention définie.

## 29. Search

Le Search Engine peut être indisponible sans empêcher :

- administration ;
- création ;
- modification ;
- imports ;
- API métier principale.

La plateforme doit pouvoir réindexer après reprise.

## 30. Plan de reprise

Documenter au minimum :

```text
restauration PostgreSQL
restauration médias
reconstruction Redis
réindexation Search
redémarrage workers
rotation secrets
```

## 31. Infrastructure as Code

À terme, versionner :

- Docker Compose / manifests ;
- scripts d’installation ;
- configuration proxy ;
- health checks ;
- scripts de sauvegarde ;
- procédures de restauration.

## 32. Hébergement

Le choix final de l’infrastructure fera l’objet d’un ADR dédié.

Le projet doit pouvoir fonctionner sur VPS ou infrastructure cloud standard sans dépendre de services propriétaires obligatoires.

## 33. Priorité de déploiement V1

```text
P1 Laravel API/Web
P2 PostgreSQL
P3 Redis
P4 Queues / Scheduler
P5 Search Engine
P6 Media Storage
P7 CI/CD
P8 Backups
P9 Monitoring / Alerting
P10 Staging / Production procedures
```

## 34. Principe final

L’infrastructure doit rester :

```text
simple au départ
+ reproductible
+ observable
+ sauvegardée
+ scalable
```

La complexité d’exploitation ne doit être ajoutée qu’en réponse à un besoin mesuré.
