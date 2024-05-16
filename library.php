<?php

require_once 'utils.php';
require_once 'storageManager.php';

// Charger les livres depuis le fichier JSON
$books = loadBooks();
if ($books === null) {
    $books = [];
}

// Affichage du menu et saisie de l'utilisateur
do {
    echo "1. Ajouter un livre\n";
    echo "2. Afficher les livres\n";
    echo "3. Modifier un livre\n";
    echo "4. Supprimer un livre\n";
    echo "5. Trier les livres\n";
    echo "6. Rechercher un livre\n";
    echo "7. Sortir\n";
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
            break;

        case 2:
            // Afficher les livres
            displayBooks($books);
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
            break;

        case 6:
            // Rechercher un livre
            $searchTerm = readline("Entrez le terme de recherche: ");
            $results = array_filter($books, function($book) use ($searchTerm) {
                return stripos($book['Titre'], $searchTerm) !== false ||
                    stripos($book['description'], $searchTerm) !== false;
            });
            displayBooks($results);
            break;

        case 7:
            exit("Au revoir!\n");

        default:
            echo "Choix non valide.\n";
    }
} while ($choice != 7);

// Fonction pour afficher les livres
function displayBooks($books) {
    foreach ($books as $id => $book) {
        $title = isset($book['Titre']) ? $book['Titre'] : '';
        $description = isset($book['description']) ? $book['description'] : '';
        $inStock = isset($book['inStock']) ? $book['inStock'] : 'No';

        echo "ID: $id, Title: $title, Description: $description, In Stock: " . ($inStock ? 'Yes' : 'No') . "\n";
    }
}
?>
