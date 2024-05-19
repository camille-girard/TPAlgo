<?php

require_once 'utils.php';
require_once 'storageManager.php';
require_once 'historyManager.php';
require_once 'bookManager.php';

// Charger l'historique depuis le fichier JSON
$history = loadHistory();
if ($history === null) {
    $history = [];
}

// Affichage du menu et saisie de l'utilisateur
do {
    $history = loadHistory();
    if ($history === null) {
        $history = [];
    }
    $books = loadBooks();
    if ($books === null) {
        $books = [];
    }
    echo "Menu:\n";
    echo "1. Ajouter un livre\n";
    echo "2. Afficher les livres\n";
    echo "3. Modifier un livre\n";
    echo "4. Supprimer un livre\n";
    echo "5. Trier les livres\n";
    echo "6. Rechercher un livre\n";
    echo "7. Afficher l'historique\n";
    echo "8. Afficher un livre\n";
    echo "9. Sortir\n";
    $choice = readline("Entrez votre choix: ");

    switch ($choice) {
        case 1: // Ajouter un livre
            $title = readline("Entrez le titre du livre: ");
            $description = readline("Entrez la description du livre: ");
            $inStock = readline("Le livre est-il en stock (oui/non): ") === 'oui';

            $bookId = createBook($title, $description, $inStock);
            echo "Livre ajouté avec succès!\n";

            // Ajouter à l'historique
            addHistoryEntry($history, "Ajout du livre ID: $bookId, Titre: $title");

            break;

        case 2: // Afficher les livres
            displayBooks($books);

            // Ajouter à l'historique
            addHistoryEntry($history, "Affichage de tous les livres");

            break;

        case 3: // Modifier un livre

            $bookId = readline("Entrez l'ID du livre à modifier: ");
            if (isset($books[$bookId])) {
                $title = readline("Entrez le nouveau titre du livre: ");
                $description = readline("Entrez la nouvelle description du livre: ");
                $inStock = readline("Le livre est-il en stock (yes/no): ") === 'yes';

                modifyBook($bookId, $title, $description, $inStock);

                echo "Livre modifié avec succès!\n";


                // Ajouter à l'historique
                addHistoryEntry($history, "Modification du livre ID: $bookId, Nouveau titre: $title");


            } else {
                echo "Livre non trouvé.\n";
            }
            break;

        case 4:
            // Supprimer un livre
            if (empty($books)) {
                echo "Il n'y a pas de livres à supprimer.\n";
                break;
            }
            $question = readline("Voulez-vous supprimer un livre par ID, par titre, par description ou s'il est en stock? (id/titre/description/inStock): ");
            $value = readline("Entrez la valeur du critère: ");
            deleteBookByCriterion($books, $question, $value);
            addHistoryEntry($history, "Suppression du livre par $question avec la valeur $value");

            break;

        case 5:
            // Trier les livres
            $column = readline("Entrez la colonne à trier (Titre, description, inStock): ");
            $order = readline("Par ordre (ASC/DESC): ");
            $sortedBooks = sortBooks($books, $column, $order);
            displayBooks($sortedBooks);

            // Ajouter à l'historique
            addHistoryEntry($history, "Tri des livres par $column en ordre $order");

            break;

        case 6:
            // Rechercher un livre
            $searchTerm = readline("Entrez le terme de recherche: ");
            $results = array_filter($books, function($book) use ($searchTerm) {
                return stripos($book['Titre'], $searchTerm) !== false ||
                    stripos($book['description'], $searchTerm) !== false;
            });
            displayBooks($results);

            // Ajouter à l'historique
            addHistoryEntry($history, "Recherche de livres avec le terme: $searchTerm");

            break;

        case 7:
            // Afficher l'historique
            if (empty($history)) {
                echo "Aucune action n'a été enregistrée dans l'historique.\n";
            } else {
                foreach ($history as $entry) {
                    echo "Timestamp: {$entry['timestamp']}, Action: {$entry['action']}\n";
                }
            }

            break;

        case 8:
            // Afficher un seul livre
            $searchTerm = readline("Entrez le terme exact de recherche: ");
            $results = array_filter($books, function($book) use ($searchTerm) {
            return $book['Titre'] === $searchTerm || $book['description'] === $searchTerm;
            });
            if ($results) {
                displayBook(reset($results));
            } else {
                echo "Aucun livre avec ce titre exact trouvé.\n";
            }
        break;

        case 9:
            exit("A bientôt!\n");

        default:
            echo "Choix non valide. Veuillez saisir un chiffre entre 1 et 8.\n";
    }
} while ($choice != 9);

// Fonction pour afficher un seul livre
function displayBook($book) {
    $title = $book['Titre'] ?? '';
    $description = $book['description'] ?? '';
    $inStock = $book['inStock'] ?? 'No';

    echo "Title: $title, Description: $description, In Stock: " . ($inStock ? 'Yes' : 'No') . "\n";
}


