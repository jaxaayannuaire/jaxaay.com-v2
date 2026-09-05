# ADR-005 — Faire de DataExchange un module prioritaire V1

- **Statut :** Accepté
- **Date :** 2026-09-05

## Contexte
Jaxaay doit migrer les données de la V1 et permettre des imports massifs futurs.

Directories Pro fournit une référence intéressante avec export/import CSV, ZIP médias et export de configuration.

## Décision
Créer un module `DataExchange` avec :

- import/export CSV ;
- mapping dynamique ;
- catégories, tags et localités ;
- champs personnalisés ;
- import de médias par ZIP ;
- Directory Blueprints JSON ;
- dry-run ;
- validation ;
- traitement par lots/queues ;
- rapport d’erreurs ;
- détection de doublons ;
- rollback lorsque possible ;
- mise à jour par référence stable.

## Conséquences
### Positives
- Migration V1 facilitée.
- Industrialisation des imports.
- Réutilisation future pour AGPS/WaPASTEF.
- Meilleure qualité des données.

### Négatives
- Complexité importante.
- Exige une gestion rigoureuse de l’idempotence et des erreurs.
