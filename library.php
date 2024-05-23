<?php

require_once 'utils.php';
require_once 'storageManager.php';
require_once 'historyManager.php';
require_once 'bookManager.php';
require_once 'functions.php';

$separator = str_repeat('-', 10);
// Affichage du menu et saisie de l'utilisateur
do {
    // Charge l'historique
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
    echo "\n";

    switch ($choice) {
        case 1: // Ajoute un livre
            $title = readline("Entrez le titre du livre: ");
            $description = readline("Entrez la description du livre: ");
            $inStock = readline("Le livre est-il en stock (oui/non): ") === 'oui';

            $bookId = createBook($books, $title, $description, $inStock);
            echo $separator . "\n";
            success("Livre ajouté avec succès!");

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
                echo $separator . "\n";
                success("Livre modifié avec succès!");

                // Ajoute à l'historique
                addHistoryEntry($history, "Modification du livre ID: $bookId, Nouveau titre: $title");


            } else {
                echo $separator . "\n";
                error("Livre non trouvé.");
            }
            break;

        case 3: // Supprime un livre
            if (empty($books)) {
                echo $separator . "\n";
                error("Il n'y a pas de livres à supprimer.");
                break;
            }

            $question = readline("Voulez-vous supprimer un livre par ID, par titre, par description ou s'il est en stock? (id/titre/description/inStock): ");
            // Vérifie si les colonnes sont valides
            if (validateColumns([$question == 'id' ? 'ID' : $question])) {
                $value = readline("Entrez la valeur du critère: ");
                deleteBookByCriterion($books, $question, $value);
                // Ajoute à l'historique
                addHistoryEntry($history, "Suppression du livre par $question avec la valeur $value");
            }
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
            // Crée un nouveau tableau pour qu'il ne trie pas selon les ID actuels
            $sortedBooks = [];
            foreach ($books as $key => $book) {
                $book['ID'] = $key;  // Utilise les clés comme ID originaux
                $sortedBooks[] = $book;
            }
            $column = readline("Entrez la colonne à trier (titre, description, inStock): ");

            // Vérifie si les colonnes de tri sont valides
            if (validateColumns([$column])) {
                $order = readline("Par ordre (ASC/DESC): ");
                mergeSort($sortedBooks, $column, $order);
                $originalBooks = [];
                //Récupère les ID originaux
                foreach ($sortedBooks as $book) {
                    $originalBooks[$book['ID']] = $book;
                }
                displayBooks($originalBooks);
                // Ajoute à l'historique
                addHistoryEntry($history, "Tri des livres par $column en ordre $order");
            }
            break;

        case 7: // Recherche un livre
            $sortedBooks = array_values($books); // Convertit le tableau associatif en indexé
            foreach ($sortedBooks as $index => $book) {
                $book['ID'] = array_keys($books)[$index];  // Conserve l'ID original dans une nouvelle clé
                $sortedBooks[$index] = $book;
            }

            $column = readline("Sur quelle colonne voulez-vous rechercher ? (titre, description, inStock, ID): ");
            // Valider la colonne avant la recherche
            if (validateColumns([$column])) {
                $searchValue = readline("Entrez la valeur à rechercher: ");

                // Conversion des entrées pour inStock et ID
                if ($column == 'inStock') {
                    $searchValue = strtolower($searchValue) == 'oui' ? true : false;
                } elseif ($column == 'ID') {
                    $searchValue = (int) $searchValue; // Convertit la valeur de recherche en numérique pour les ID
                }

                // Tri avant la recherche
                mergeSort($sortedBooks, $column, 'ASC');

                $indices = binarySearchAll($sortedBooks, $column, $searchValue);
                if (!empty($indices)) {
                    echo "Livres trouvés:\n";
                    echo "\n";
                    $results = [];
                    foreach ($indices as $index) {
                        $results[$sortedBooks[$index]['ID']] = $sortedBooks[$index];
                    }
                    displayBooks($results);
                } else {
                    echo $separator . "\n";
                    error("Aucun livre trouvé pour cette valeur.");
                }
                // Ajoute à l'historique
                addHistoryEntry($history, "Recherche du livre par $column avec la valeur " . json_encode($searchValue));
            }
            break;



        case 8:
            // Affiche l'historique
            if (empty($history)) {
                error("Aucune action n'a été enregistrée dans l'historique.");
            } else {
                foreach ($history as $entry) {
                    echo "Timestamp: {$entry['timestamp']}, Action: {$entry['action']}\n";
                }
            }

            break;

        case 9:

            exit("A bientôt!\n");

        default:
            error("Choix non valide. Veuillez saisir un chiffre entre 1 et 8.");
    }
} while ($choice != 9);

