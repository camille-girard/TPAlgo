<?php
include 'bookManager.php';
include 'utils.php';
function mainMenu() {
    while (true) {
        echo "\n1. Ajouter un livre\n2. Afficher les livres\n3. Modifier un livre\n4. Supprimer un livre\n5. Trier les livres\n6. Rechercher un livre\n7. Sortir\nEntrez votre choix: ";
        $choice = trim(fgets(STDIN));

        switch ($choice) {
            case '1':
                echo "Entrez un nom: ";
                $title = trim(fgets(STDIN));
                echo "Entrez une description: ";
                $description = trim(fgets(STDIN));
                echo "Est-ce en stock ? (oui/non): ";
                $inStock = trim(fgets(STDIN)) === 'yes';
                $bookId = createBook($title, $description, $inStock);
                echo "Livre ajouté avec succès avec ID: $bookId\n";
                break;
            case '2':
                displayBooks();
                break;
            case '3':
                echo "Entrez l'ID du livre à modifier: ";
                $id = trim(fgets(STDIN));
                echo "Entrez le nouveau nom: ";
                $title = trim(fgets(STDIN));
                echo "Entrez la nouvelle description): ";
                $description = trim(fgets(STDIN));
                echo "Est-ce en stock ? (oui/non): ";
                $stockInput = trim(fgets(STDIN));
                $inStock = $stockInput === '' ? null : $stockInput === 'yes';
                modifyBook($id, $title, $description, $inStock);
                break;
            case '4':
                echo "Entrez le titre du livre à supprimer: ";
                $id = trim(fgets(STDIN));
                deleteBook($id);
                break;
            case '5':
                echo "Entrez la colonne à trier (nom, description, inStock): ";
                $column = trim(fgets(STDIN));
                echo "Par ordre (ASC/DESC): ";
                $order = trim(fgets(STDIN));
                $sortedBooks = mergeSort(loadBooks(), $column, $order);
                foreach ($sortedBooks as $book) {
                    echo "ID: {$book['id']}, Name: {$book['name']}, Description: {$book['description']}, In Stock: " . ($book['inStock'] ? 'Yes' : 'No') . "\n";
                }
                break;
            case '6':
                echo "Entrez la colonne par laquelle effectuer la recherche (nom, description, inStock, identifiant): ";
                $column = trim(fgets(STDIN));
                echo "Entrez la valeur recherchée: ";
                $value = trim(fgets(STDIN));
                $index = binarySearch(loadBooks(), $column, $value);
                if ($index != -1) {
                    echo "Livre trouvé: " . implode(', ', loadBooks()[$index]) . "\n";
                } else {
                    echo "Livre non trouvé.\n";
                }
                break;
            case '7':
                return;
            default:
                echo "Choix invalide. Veuillez saisir un chiffre entre 1 et 7.\n";
        }
    }
}

mainMenu();
?>
