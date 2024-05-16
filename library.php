<?php

require_once 'utils.php';
require_once 'storageManager.php';
require_once 'historyManager.php';

// Charger les livres depuis le fichier JSON
$books = loadBooks();
if ($books === null) {
    $books = [];
}

// Charger l'historique depuis le fichier JSON
$history = loadHistory();
if ($history === null) {
    $history = [];
}

// Affichage du menu et saisie de l'utilisateur
do {
    echo "1. Ajouter un livre\n";
    echo "2. Afficher les livres\n";
    echo "3. Modifier un livre\n";
    echo "4. Supprimer un livre\n";
    echo "5. Trier les livres\n";
    echo "6. Rechercher un livre\n";
    echo "7. Afficher l'historique\n";
    echo "8. Sortir\n";
    $choice = readline("Entrez votre choix: ");

    switch ($choice) {
        case 1:
            // Ajouter un livre
            $title = readline("Entrez le titre du livre: ");
            $description = readline("Entrez la description du livre: ");
            $inStock = readline("Le livre est-il en stock (yes/no): ") === 'yes';

            $bookId = 'book_' . uniqid();
            $books[$bookId] = [
                'Titre' => $title,
                'description' => $description,
                'inStock' => $inStock
            ];

            saveBooks($books);
            echo "Livre ajouté avec succès!\n";

            // Ajouter à l'historique
            addHistoryEntry($history, "Ajout du livre ID: $bookId, Titre: $title");

            break;

        case 2:
            // Afficher les livres
            displayBooks($books);

            // Ajouter à l'historique
            addHistoryEntry($history, "Affichage de tous les livres");

            break;

        case 3:
            // Modifier un livre
            $bookId = readline("Entrez l'ID du livre à modifier: ");
            if (isset($books[$bookId])) {
                $title = readline("Entrez le nouveau titre du livre: ");
                $description = readline("Entrez la nouvelle description du livre: ");
                $inStock = readline("Le livre est-il en stock (yes/no): ") === 'yes';

                $books[$bookId] = [
                    'Titre' => $title,
                    'description' => $description,
                    'inStock' => $inStock
                ];

                saveBooks($books);
                echo "Livre modifié avec succès!\n";

                // Ajouter à l'historique
                addHistoryEntry($history, "Modification du livre ID: $bookId, Nouveau titre: $title");

            } else {
                echo "Livre non trouvé.\n";
            }
            break;

        case 4:
            // Supprimer un livre
            $bookId = readline("Entrez l'ID du livre à supprimer: ");
            if (isset($books[$bookId])) {
                unset($books[$bookId]);
                saveBooks($books);
                echo "Livre supprimé avec succès!\n";

                // Ajouter à l'historique
                addHistoryEntry($history, "Suppression du livre ID: $bookId");

            } else {
                echo "Livre non trouvé.\n";
            }
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
            echo "A bientôt!\n";
            exit();

        default:
            echo "Choix non valide.Veuillez saisir un chiffre entre 1 et 8.\n";
    }
} while ($choice != 8);

// Fonction pour afficher les livres
function displayBooks($books) {
    foreach ($books as $id => $book) {
        $title = $book['Titre'] ?? '';
        $description = $book['description'] ?? '';
        $inStock = $book['inStock'] ?? 'No';

        echo "ID: $id, Title: $title, Description: $description, In Stock: " . ($inStock ? 'Yes' : 'No') . "\n";
    }
}
?>
