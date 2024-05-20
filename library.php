<?php

require_once 'utils.php';
require_once 'storageManager.php';
require_once 'historyManager.php';
require_once 'bookManager.php';


$separator = str_repeat('-', 10);
// Affichage du menu et saisie de l'utilisateur
do {
    // Charger l'historique
    $history = loadHistory();
    if ($history === null) {
        $history = [];
    }

    // Charge les livres
    $books = loadBooks();
    if ($books === null) {
        $books = [];
    }

    echo $separator . "\n";
    echo "Menu:\n";
    echo "1. Ajouter un livre\n";
    echo "2. Modifier un livre\n";
    echo "3. Supprimer un livre\n";
    echo "4. Afficher les livres\n";
    echo "5. Afficher un livre\n";
    echo "6. Trier les livres\n";
    echo "7. Rechercher un livre\n";
    echo "8. Afficher l'historique\n";
    echo "9. Sortir\n";
    echo $separator . "\n";
    echo "\n";
    $choice = readline("Entrez votre choix: ");

    switch ($choice) {
        case 1: // Ajoute un livre
            $title = readline("Entrez le titre du livre: ");
            $description = readline("Entrez la description du livre: ");
            $inStock = readline("Le livre est-il en stock (oui/non): ") === 'oui';

            $bookId = createBook($books, $title, $description, $inStock);
            echo "Livre ajouté avec succès!\n";

            // Ajoute à l'historique
            addHistoryEntry($history, "Ajout du livre ID: $bookId, Titre: $title");

            break;

        case 2: // Modifie un livre
            $bookId = readline("Entrez l'ID du livre à modifier: ");
            if (isset($books[$bookId])) {
                $title = readline("Entrez le nouveau titre du livre: ");
                $description = readline("Entrez la nouvelle description du livre: ");
                $inStock = readline("Le livre est-il en stock (oui/non): ") === 'oui';

                modifyBook($books, $bookId, $title, $description, $inStock);

                echo "Livre modifié avec succès!\n";


                // Ajoute à l'historique
                addHistoryEntry($history, "Modification du livre ID: $bookId, Nouveau titre: $title");


            } else {
                echo "Livre non trouvé.\n";
            }
            break;

        case 3: // Supprime un livre
            if (empty($books)) {
                echo "Il n'y a pas de livres à supprimer.\n";
                break;
            }

            $question = readline("Voulez-vous supprimer un livre par ID, par titre, par description ou s'il est en stock? (id/titre/description/inStock): ");
            $value = readline("Entrez la valeur du critère: ");
            deleteBookByCriterion($books, $question, $value);
            addHistoryEntry($history, "Suppression du livre par $question avec la valeur $value");

            break;

        case 4: // Affiche les livres
            displayBooks($books);

            // Ajoute à l'historique
            addHistoryEntry($history, "Affichage de tous les livres");

            break;

        case 5: // Affiche un seul livre
            $searchTerm = readline("Entrez le terme de recherche: ");
            searchAndDisplayBooks($books, $searchTerm);
            break;

        case 6:  // Trie les livres
            $books = loadBooks();
            foreach ($books as $key => $book) {
                $books[$key]['originalID'] = $key;  // Utilise les clés comme ID originaux
            }
            $column = readline("Entrez la colonne à trier (titre, description, inStock): ");
            $order = readline("Par ordre (ASC/DESC): ");
            mergeSort($books, $column, $order);
            displayBooks($books);
            addHistoryEntry($history, "Tri des livres par $column en ordre $order");
            break;

        case 7: // Rechercher un livre
            $books = loadBooks();
            $books = array_values($books); // Convertit le tableau associatif en tableau indexé
            foreach ($books as $index => $book) {
                $books[$index]['ID'] = $index + 1;  // Ajoute l'ID basé sur l'index + 1 pour correspondre à vos ID
            }
            $column = readline("Sur quelle colonne voulez-vous rechercher ? (titre, description, inStock, ID): ");
            $searchValue = readline("Entrez la valeur à rechercher: ");
            mergeSort($books, $column, 'ASC'); // Tri avant la recherche
            $index = binarySearch($books, $column, $searchValue);
            if ($index != -1) {
                echo "Livre trouvé:\n";
                displayBooks([$books[$index]]);  // Affiche seulement le livre trouvé
            } else {
                echo "Aucun livre trouvé pour cette valeur.\n";
            }
            addHistoryEntry($history, "Recherche du livre par $column avec la valeur $searchValue");
            break;


        case 8:
            // Affiche l'historique
            if (empty($history)) {
                echo "Aucune action n'a été enregistrée dans l'historique.\n";
            } else {
                foreach ($history as $entry) {
                    echo "Timestamp: {$entry['timestamp']}, Action: {$entry['action']}\n";
                }
            }

            break;

        case 9:
            exit("A bientôt!\n");

        default:
            echo "Choix non valide. Veuillez saisir un chiffre entre 1 et 8.\n";
    }
} while ($choice != 9);


