# Jaxaay Annuaire V2 — SEO, Views et comparaison

**Version : 0.1**  
**Statut : Spécification fonctionnelle V1**

## 1. SEO

Chaque fiche publique doit pouvoir produire :

- slug ;
- meta title ;
- meta description ;
- canonical ;
- OpenGraph ;
- breadcrumbs ;
- Schema.org ;
- sitemap.

## 2. Landing pages

Exemples :

```text
/ophtalmologues/kaolack
/restaurants/dakar
/emplois/informatique/dakar
```

Les landing pages doivent utiliser des combinaisons contrôlées de taxonomie + localisation.

## 3. Schema.org

Le type de schéma dépend du contenu :

- Person ;
- Organization ;
- LocalBusiness ;
- JobPosting ;
- Article / DigitalDocument selon cas ;
- autres types compatibles.

## 4. Views

Séparation :

```text
Data
→ Query
→ View
→ Display
```

Affichages :

- List ;
- Grid ;
- Cards ;
- Map ;
- List + Map ;
- Carousel.

## 5. Responsive

Les vues doivent rester légères et adaptées mobile.

## 6. Bloc "Voir aussi"

Les pages peuvent afficher des contenus reliés :

- même catégorie ;
- même localité ;
- même organisation ;
- relations explicites ;
- mots-clés proches.

## 7. Comparaison

Les fiches compatibles peuvent être comparées.

Le moteur sélectionne les champs déclarés comparables dans le ListingType.

Exemples :

- hôtels ;
- écoles ;
- véhicules ;
- services ;
- offres.

## 8. SEO des recherches

Toutes les recherches utilisateur ne doivent pas être indexées.

Seules les pages stratégiques définies comme landing pages sont exposées au référencement.
