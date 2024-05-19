<?php
include_once 'storageManager.php';

// Charger les livres depuis le fichier JSON
function createBook($title, $description, $inStock) {
    $books = loadBooks();

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
    if ($title !== '') $books[$id]['titre'] = $title;
    if ($description !== '') $books[$id]['description'] = $description;
    if ($inStock !== null) $books[$id]['inStock'] = $inStock;
    saveBooks($books);
}

function deleteBookByTitle($title) {
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



// Fonction pour afficher les livres
function displayBooks($books) {
    // Déterminer la longueur maximale pour chaque colonne pour un alignement propre
    $maxLengthId = $maxLengthTitle = $maxLengthDesc = $maxLengthStock = 0;

    foreach ($books as $id => $book) {
        $maxLengthId = max($maxLengthId, strlen($id));
        $maxLengthTitle = max($maxLengthTitle, strlen($book['titre'] ?? ''));
        $maxLengthDesc = max($maxLengthDesc, strlen($book['description'] ?? ''));
    }

    // En-tête du tableau
    echo "+-" . str_repeat('-', $maxLengthId) . "-+-" . str_repeat('-', $maxLengthTitle) . "-+-" . str_repeat('-', $maxLengthDesc) . "-+-" . str_repeat('-', 10) . "-+\n";
    echo "| " . str_pad("ID", $maxLengthId) . " | " . str_pad("Title", $maxLengthTitle) . " | " . str_pad("Description", $maxLengthDesc) . " | " . str_pad("In Stock", 10) . " |\n";
    echo "+-" . str_repeat('-', $maxLengthId) . "-+-" . str_repeat('-', $maxLengthTitle) . "-+-" . str_repeat('-', $maxLengthDesc) . "-+-" . str_repeat('-', 10) . "-+\n";

    // Afficher les données de chaque livre
    foreach ($books as $id => $book) {
        $title = $book['titre'] ?? '';
        $description = $book['description'] ?? '';
        $inStock = $book['inStock'] ?? 'No';
        echo "| " . str_pad($id, $maxLengthId) . " | " . str_pad($title, $maxLengthTitle) . " | " . str_pad($description, $maxLengthDesc) . " | " . str_pad(($inStock ? 'Yes' : 'No'), 10) . " |\n";
    }

    // Pied de page du tableau
    echo "+-" . str_repeat('-', $maxLengthId) . "-+-" . str_repeat('-', $maxLengthTitle) . "-+-" . str_repeat('-', $maxLengthDesc) . "-+-" . str_repeat('-', 10) . "-+\n";
}

