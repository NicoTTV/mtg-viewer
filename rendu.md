# Rendu exercices

## Exercice 0

- Import de 10,000 cartes en 7.15 secondes.
- Import de 30,000 cartes en 14.99 secondes.
- Import de toutes les cartes en 40.57 secondes.

## Exercice 1

- Ajout de logs pour chaque appel à l'API
- Ajout de logs pour le début, la fin, la durée et les erreurs de l'import :
- Utilisation de deux méthodes pour les logs : 
    - Utilisation de la méthode `error` de l'interface `LoggerInterface` pour les erreurs.
    - Utilisation de la méthode `info` de l'interface `LoggerInterface` pour les informations.
- Utilisation de microtime pour calculer la durée de l'import.
- Ajout des variables `uuid` et `card` dans les logs pour identifier les cartes importées pour faciliter le débuggage.

## Exercice 2
- Ajout d'une route `/search` pour la recherche de carte.
    - La route ne fonctionnait pas au début, car elle était placée après la route de détail de carte, la route de détail de carte prenait le dessus. 
- Ajout d'une méthode `search` dans le controller `CardController` pour la recherche de carte.
- Création du template `SearchPage.vue` pour la recherche de carte.
- Ajout de la méthode `searchCard` dans le service `CardService` pour la recherche de carte.

## Exercice 3
- Ajout d'un filtre sur le `setCode` pour la recherche de carte et le listing de carte.