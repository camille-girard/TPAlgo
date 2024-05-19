<?php
include_once 'storageManager.php';

// Charger les livres depuis le fichier JSON
function createBook($title, $description, $inStock) {
    $books = loadBooks();

    // Initialiser l'ID maximal trouvé à 0
    $maxId = 0;

    // Parcourir les clés pour trouver le plus grand ID
    foreach ($books as $key => $value) {
        $idNumber = intval(str_replace('book_', '', $key));  // Extrait le numéro après 'book_'
        if ($idNumber > $maxId) {
            $maxId = $idNumber;
        }
    }

    // L'ID suivant est le maximum actuel + 1
    $newId = 'book_' . ($maxId + 1);

    // Ajouter le nouveau livre avec l'ID incrémenté
    $books[$newId] = ['titre' => $title, 'description' => $description, 'inStock' => $inStock];

    // Sauvegarder les livres mis à jour
    saveBooks($books);

    return $newId;
}


function modifyBook($id, $title, $description, $inStock) {
    $books = loadBooks();
    if (!isset($books[$id])) {
        echo "Aucun livre n'a été trouvé avec cet id: $id.\n";
        return;
    }
    if ($title !== '') $books[$id]['titre'] = $title;
    if ($description !== '') $books[$id]['description'] = $description;
    if ($inStock !== null) $books[$id]['inStock'] = $inStock;
    saveBooks($books);
    echo "Le livre est bien modifié.\n";
}

function deleteBook($title) {
    $books = loadBooks();
    $found = false;
    foreach ($books as $id => $book) {
        if ($book['titre'] == $title) {
            unset($books[$id]);
            $found = true;
        }
    }
    if ($found) {
        saveBooks($books);
        echo "Le livre '$title' est supprimé.\n";
    } else {
        echo "Il n'y aucun livre '$title'.\n";
    }
}


function displayBooks() {
    $books = loadBooks();
    foreach ($books as $id => $book) {
        echo "ID: $id, Titre: {$book['titre']}, Description: {$book['description']}, En Stock: " . ($book['inStock'] ? 'Oui' : 'Non') . "\n";
    }
}

// Fonction pour afficher les livres
/*function displayBooks($books) {
    foreach ($books as $id => $book) {
        $title = $book['Titre'] ?? '';
        $description = $book['description'] ?? '';
        $inStock = $book['inStock'] ?? 'No';

        echo "ID: $id, Title: $title, Description: $description, In Stock: " . ($inStock ? 'Yes' : 'No') . "\n";
    }
}
?>*/
