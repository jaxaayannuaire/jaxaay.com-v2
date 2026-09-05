# ADR-010 — Reporter la marketplace après la V1

- **Statut :** Accepté
- **Date :** 2026-09-05

## Contexte
Bulistio, Jumia et Active eCommerce montrent l’intérêt futur d’une marketplace, mais la priorité actuelle de Jaxaay est de construire un annuaire performant, léger et configurable.

## Décision
La marketplace transactionnelle est hors périmètre V1.

Ne pas introduire prématurément :
- panier ;
- commandes ;
- stocks ;
- wallet ;
- commissions ;
- payouts ;
- livraison.

L’architecture doit seulement éviter de bloquer leur ajout futur comme domaine séparé.

## Priorités V1
- Directory Engine ;
- Profils ;
- Entreprises ;
- Annonces ;
- Documents/Archives ;
- Search ;
- Location Core ;
- DataExchange ;
- Claim/KYC ;
- Modération ;
- SEO ;
- Leads ;
- External Profiles.

## Conséquences
### Positives
- Réduction du scope.
- Meilleure qualité du moteur d’annuaire.
- Développement plus maîtrisable.

### Négatives
- Fonctions transactionnelles différées à V2/V3.
