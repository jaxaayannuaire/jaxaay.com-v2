# État du bootstrap Jaxaay V2

Le scaffold Laravel 13 est initialisé dans `apps/api` (Laravel Framework 13.30.1).

Cette étape ne contient aucun domaine métier, aucune migration métier, aucun
module marketplace et aucun moteur de recherche externe. PostgreSQL reste la
base cible documentée ; la configuration locale Laravel par défaut n'est pas
considérée comme l'architecture finale.

Le scaffold Flutter Android/Web est initialisé dans `apps/mobile`.
`dart format`, `flutter analyze`, `flutter test` et `flutter build web`
passent. La cible Windows Desktop reste non activée, Visual Studio C++
n'étant pas installé.
